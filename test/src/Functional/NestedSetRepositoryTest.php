<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Functional;

use Bupy7\Doctrine\NestedSet\NestedSetRepositoryAbstract;
use Bupy7\Doctrine\NestedSet\Test\Assert\Category;
use Bupy7\Doctrine\NestedSet\Test\FunctionalTestCase;
use Doctrine\DBAL\TransactionIsolationLevel;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use function array_keys;
use function array_map;
use function array_unique;

class NestedSetRepositoryTest extends FunctionalTestCase
{
    public function testCount(): void
    {
        $this->assertCount(91, $this->getNestedSetRepository()->findAll());
    }

    public function testFindOneParent(): void
    {
        /** @var Category $childCategory */
        $childCategory = $this->getNestedSetRepository()->find(69);
        $this->assertNotNull($childCategory, 'childCategory is not null');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->findOneParent($childCategory);
        $this->assertNotNull($parentCategory, 'parentCategory is not null');

        $this->assertEquals(71, $parentCategory->getId());
    }

    public function testFindChildren(): void
    {
        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(10);
        $this->assertNotNull($parentCategory, 'parentCategory is not null');

        /** @var Category[] $childrenCategories */
        $childrenCategories = $this->getNestedSetRepository()->findChildren($parentCategory);
        $this->assertNotEmpty($childrenCategories, 'childrenCategories is not empty');

        $ids = [];
        foreach ($childrenCategories as $category) {
            $ids[] = $category->getId();
        }
        $this->assertEquals([27, 71, 16, 35, 36, 67, 90, 91], $ids);
    }

    public function testFindDescendants(): void
    {
        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(10);
        $this->assertNotNull($parentCategory, 'parentCategory is not null');

        $descendantsCategories = $this->getNestedSetRepository()->findDescendants($parentCategory);
        $this->assertNotEmpty($descendantsCategories, 'descendantsCategories is not empty');

        $ids = [];
        foreach ($descendantsCategories as $category) {
            $ids[] = $category->getId();
        }
        $this->assertEquals([27, 71, 72, 75, 73, 68, 69, 74, 76, 16, 35, 36, 67, 90, 91], $ids);
    }

    public function testFindAll(): void
    {
        $categories = $this->getNestedSetRepository()->findAll();
        $ids = [];
        foreach ($categories as $category) {
            $ids[] = $category->getId();
        }
        $this->assertEquals([
            1, 2, 4, 3, 56, 61, 64, 62, 66, 84, 28, 38, 41, 39, 42, 40, 51, 11, 12, 13, 29, 37, 60, 65, 17, 18, 81,
            70, 19, 55, 20, 22, 57, 50, 5, 6, 7, 9, 82, 79, 86, 10, 27, 71, 72, 75, 73, 68, 69, 74, 76, 16, 35, 36,
            67, 90, 91, 8, 30, 31, 85, 24, 25, 26, 54, 89, 33, 34, 49, 45, 46, 47, 48, 63, 43, 44, 52, 53, 83, 14,
            77, 15, 21, 23, 58, 32, 59, 78, 87, 88, 80,
        ], $ids);
    }

    public function testFindRoots(): void
    {
        $rootCategories = $this->getNestedSetRepository()->findRoots();
        $ids = [];
        foreach ($rootCategories as $category) {
            $ids[] = $category->getId();
        }
        $this->assertEquals([
            1, 28, 11, 17, 5, 6, 7, 9, 10, 8, 24, 33,
            45,43, 14, 15, 21, 23, 58, 32, 59, 78, 80
        ], $ids);
    }
    
    public function testFindAncestors(): void
    {
        /** @var Category $childCategory */
        $childCategory = $this->getNestedSetRepository()->find(68);
        $this->assertNotNull($childCategory, 'childCategory is not null');

        $ancestorCategories = $this->getNestedSetRepository()->findAncestors($childCategory);
        $this->assertNotEmpty($ancestorCategories, 'ancestorCategories is not empty');

        $this->assertEquals(
            [71, 10],
            array_map(function (Category $category) {
                return $category->getId();
            }, $ancestorCategories)
        );
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testAddCategoryAsRoot(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        $this->getNestedSetRepository()->append($category);

        $this->assertNotNull($category->getId());

        $this->entityManager->clear();

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');

        $this->assertNotNull($result);
        $this->assertEquals(24, $result->getRootKey());
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals(1, $result->getLevel());
        $this->assertEquals(1, $result->getLeftKey());
        $this->assertEquals($result->getLeftKey() + 1, $result->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testAddCategoryAsChild(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetRepository()->append($category, $parentCategory);

        $this->assertNotNull($category->getId());
        $this->assertNotNull($parentCategory->getId());

        $this->entityManager->clear();

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(42);

        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getRootKey());
        $this->assertEquals($parentCategory->getLevel() + 1, $result->getLevel());
        $this->assertEquals(4, $result->getLevel());
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals(8, $result->getLeftKey());
        $this->assertEquals(9, $result->getRightKey());

        // first parent
        $this->assertEquals(2, $parentCategory->getRootKey());
        $this->assertEquals(3, $parentCategory->getLevel());
        $this->assertEquals(7, $parentCategory->getLeftKey());
        $this->assertEquals(10, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getRootKey());
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(6, $parentCategory->getLeftKey());
        $this->assertEquals(11, $parentCategory->getRightKey());

        // thirth parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(2, $parentCategory->getRootKey());
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(1, $parentCategory->getLeftKey());
        $this->assertEquals(16, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testPrependCategoryAsRoot(): void
    {
        $categories = $this->getNestedSetRepository()->findAll();
        $expectedCategories = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $expectedCategories[] = [
                $category->getId(),
                $category->getRootKey() + 1,
                $category->getLevel(),
                $category->getLeftKey(),
                $category->getRightKey()
            ];
        }

        $category = new Category();
        $category->setName('Example Test Name 1');

        $this->getNestedSetRepository()->prepend($category);

        $this->assertNotNull($category->getId());

        $this->entityManager->clear();

        /** @var Category|null $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');

        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getRootKey());
        $this->assertEquals(1, $result->getLevel());
        $this->assertEquals(1, $result->getLeftKey());
        $this->assertEquals(2, $result->getRightKey());

        $categories = $this->getNestedSetRepository()->findAll();
        $actualCategories = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            if ($category->getId() === $result->getId()) {
                continue;
            }
            $actualCategories[] = [
                $category->getId(),
                $category->getRootKey(),
                $category->getLevel(),
                $category->getLeftKey(),
                $category->getRightKey()
            ];
        }

        $this->assertEquals($expectedCategories, $actualCategories);

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testPrependCategoryAsChild(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones

        $this->getNestedSetRepository()->prepend($category, $parentCategory);

        $this->assertNotNull($category->getId());
        $this->assertNotNull($parentCategory->getId());

        $this->entityManager->clear();

        /** @var Category|null $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');

        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getRootKey());
        $this->assertEquals(3, $result->getLevel());
        $this->assertEquals(7, $result->getLeftKey());
        $this->assertEquals(8, $result->getRightKey());

        $expectedCategories = [
            [28, 2, 1, 1, 16],
            [38, 2, 2, 2, 3],
            [41, 2, 2, 4, 5],
            [39, 2, 2, 6, 11],
            [$result->getId(), 2, 3, 7, 8],
            [42, 2, 3, 9, 10],
            [40, 2, 2, 12, 13],
            [51, 2, 2, 14, 15],
        ];
        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $categories = $this->getNestedSetRepository()->findDescendants($parentCategory);
        $actualCategories = [
            [
                $parentCategory->getId(),
                $parentCategory->getRootKey(),
                $parentCategory->getLevel(),
                $parentCategory->getLeftKey(),
                $parentCategory->getRightKey(),
            ],
        ];
        /** @var Category $category */
        foreach ($categories as $category) {
            $actualCategories[] = [
                $category->getId(),
                $category->getRootKey(),
                $category->getLevel(),
                $category->getLeftKey(),
                $category->getRightKey()
            ];
        }

        $this->assertEquals($expectedCategories, $actualCategories);

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testRemoveChildWithoutChildren(): void
    {
        /** @var Category $category */
        $category = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetRepository()->remove($category);

        $this->assertNull($category->getId());

        $this->entityManager->clear();

        // first parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getRootKey());
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(6, $parentCategory->getLeftKey());
        $this->assertEquals(7, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(2, $parentCategory->getRootKey());
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(1, $parentCategory->getLeftKey());
        $this->assertEquals(12, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testRemoveChildWithDescendants(): void
    {
        /** @var Category $category */
        $category = $this->getNestedSetRepository()->find(39); // PBX and system phones

        $this->getNestedSetRepository()->remove($category);

        $this->assertNull($category->getId());

        $this->entityManager->clear();

        // first child
        $childCategory = $this->getNestedSetRepository()->find(42); // System terminals
        $this->assertNull($childCategory);

        // first parent
        $childCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertNotNull($childCategory);
        $this->assertEquals(2, $childCategory->getRootKey());
        $this->assertEquals(1, $childCategory->getLevel());
        $this->assertEquals(1, $childCategory->getLeftKey());
        $this->assertEquals(10, $childCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testRemoveChildWithDescendantsAndTransaction(): void
    {
        $oldLevel = $this->entityManager->getConnection()->getTransactionIsolation();
        $this->entityManager->getConnection()->setTransactionIsolation(TransactionIsolationLevel::SERIALIZABLE);
        $this->entityManager->beginTransaction();
        try {
            $this->testRemoveChildWithDescendants();

            $this->entityManager->commit();
        } catch (ORMException | MappingException $e) {
            $this->entityManager->rollback();
            throw $e;
        } finally {
            $this->entityManager->getConnection()->setTransactionIsolation($oldLevel);
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function testPrependCategoryAsChildAndTransaction(): void
    {
        $oldLevel = $this->entityManager->getConnection()->getTransactionIsolation();
        $this->entityManager->getConnection()->setTransactionIsolation(TransactionIsolationLevel::SERIALIZABLE);
        $this->entityManager->beginTransaction();
        try {
            $this->testPrependCategoryAsChild();

            $this->entityManager->commit();
        } catch (ORMException | MappingException $e) {
            $this->entityManager->rollback();
            throw $e;
        } finally {
            $this->entityManager->getConnection()->setTransactionIsolation($oldLevel);
        }
    }

    private function thereAreAllUniqueKeys(): bool
    {
        $categories = $this->getNestedSetRepository()->findAll();

        $keys = [];
        foreach ($categories as $category) {
            $rootKey = $category->getRootKey();

            if (!isset($keys[$rootKey])) {
                $keys[$rootKey] = ['left' => [], 'right' => []];
            }

            $keys[$rootKey]['left'][] = $category->getLeftKey();
            $keys[$rootKey]['right'][] = $category->getRightKey();
        }

        $isUnique = array_keys($keys) === array_unique(array_keys($keys));

        foreach ($keys as $group) {
            $isUnique = $group['left'] === array_unique($group['left'])
                && $group['right'] === array_unique($group['right']);
        }

        return $isUnique;
    }

    private function getNestedSetRepository(): NestedSetRepositoryAbstract
    {
        return $this->entityManager->getRepository(Category::class);
    }
}
