<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Functional;

use Bupy7\Doctrine\NestedSet\NestedSetRepositoryAbstract;
use Bupy7\Doctrine\NestedSet\NestedSetService;
use Bupy7\Doctrine\NestedSet\Test\Assert\Category;
use Bupy7\Doctrine\NestedSet\Test\FunctionalTestCase;
use function array_keys;
use function array_unique;

class NestedSetServiceTest extends FunctionalTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testAddCategoryAsRoot(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        $this->getNestedSetService()->append($category);

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(24, $result->getRoot());
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals(1, $result->getLevel());
        $this->assertEquals(1, $result->getLeftKey());
        $this->assertEquals($result->getLeftKey() + 1, $result->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testAddCategoryAsChild(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetService()->append($category, $parentCategory);

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getRoot());
        $this->assertEquals($parentCategory->getLevel() + 1, $result->getLevel());
        $this->assertEquals(4, $result->getLevel());
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals(8, $result->getLeftKey());
        $this->assertEquals(9, $result->getRightKey());

        // first parent
        $this->assertEquals(2, $parentCategory->getRoot());
        $this->assertEquals(3, $parentCategory->getLevel());
        $this->assertEquals(7, $parentCategory->getLeftKey());
        $this->assertEquals(10, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getRoot());
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(6, $parentCategory->getLeftKey());
        $this->assertEquals(11, $parentCategory->getRightKey());

        // thirth parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(2, $parentCategory->getRoot());
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(1, $parentCategory->getLeftKey());
        $this->assertEquals(16, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function prependCategoryAsRoot(): void
    {
        $categories = $this->getNestedSetRepository()->findBy([], ['leftKey' => 'ASC']);
        $expectedCategories = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $expectedCategories[] = [
                $category->getId(),
                $category->getRoot() + 1,
                $category->getLevel(),
                $category->getLeftKey() + 2,
                $category->getRightKey() + 2
            ];
        }

        $category = new Category();
        $category->setName('Example Test Name 1');

        $this->getNestedSetService()->prepend($category);

        /** @var Category|null $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getRoot());
        $this->assertEquals(1, $result->getLevel());
        $this->assertEquals(1, $result->getLeftKey());
        $this->assertEquals(2, $result->getRightKey());

        $categories = $this->getNestedSetRepository()->findBy([], ['leftKey' => 'ASC']);
        $actualCategories = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            if ($category->getId() === $result->getId()) {
                continue;
            }
            $actualCategories[] = [
                $category->getId(),
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
     */
    public function prependCategoryAsChild(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones

        $this->getNestedSetService()->prepend($category, $parentCategory);

        /** @var Category|null $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->getLevel());
        $this->assertEquals(27, $result->getLeftKey());
        $this->assertEquals(28, $result->getRightKey());

        $expectedCategories = [
            [28, 1, 21, 36],
            [38, 2, 22, 23],
            [41, 2, 24, 25],
            [39, 2, 26, 31],
            [$result->getId(), 3, 27, 28],
            [42, 3, 29, 30],
            [40, 2, 32, 33],
            [51, 2, 34, 35],
        ];
        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $categories = $this->getNestedSetRepository()->findDescendants($parentCategory);
        $actualCategories = [
            [
                $parentCategory->getId(),
                $parentCategory->getLevel(),
                $parentCategory->getLeftKey(),
                $parentCategory->getRightKey(),
            ],
        ];
        /** @var Category $category */
        foreach ($categories as $category) {
            $actualCategories[] = [
                $category->getId(),
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
     */
    public function testRemoveChildWithoutChildren(): void
    {
        /** @var Category $category */
        $category = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetService()->remove($category);

        // first parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getRoot());
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(6, $parentCategory->getLeftKey());
        $this->assertEquals(7, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(2, $parentCategory->getRoot());
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(1, $parentCategory->getLeftKey());
        $this->assertEquals(12, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testRemoveChildWithDescendants(): void
    {
        /** @var Category $category */
        $category = $this->getNestedSetRepository()->find(39); // PBX and system phones

        $this->getNestedSetService()->remove($category);

        // first child
        $childCategory = $this->getNestedSetRepository()->find(42); // System terminals
        $this->assertNull($childCategory);

        // first parent
        $childCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertNotNull($childCategory);
        $this->assertEquals(2, $childCategory->getRoot());
        $this->assertEquals(1, $childCategory->getLevel());
        $this->assertEquals(1, $childCategory->getLeftKey());
        $this->assertEquals(10, $childCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    private function thereAreAllUniqueKeys(): bool
    {
        $categories = $this->getNestedSetRepository()->findAll();

        $keys = [];
        foreach ($categories as $category) {
            $root = $category->getRoot();

            if (!isset($keys[$root])) {
                $keys[$root] = ['left' => [], 'right' => []];
            }

            $keys[$root]['left'][] = $category->getLeftKey();
            $keys[$root]['right'][] = $category->getRightKey();
        }

        $isUnique = array_keys($keys) === array_unique(array_keys($keys));

        foreach ($keys as $group) {
            $isUnique = $group['left'] === array_unique($group['left'])
                && $group['right'] === array_unique($group['right']);
        }

        return $isUnique;
    }

    private function getNestedSetService(): NestedSetService
    {
        return new NestedSetService($this->entityManager, $this->getNestedSetRepository());
    }

    private function getNestedSetRepository(): NestedSetRepositoryAbstract
    {
        return $this->entityManager->getRepository(Category::class);
    }
}
