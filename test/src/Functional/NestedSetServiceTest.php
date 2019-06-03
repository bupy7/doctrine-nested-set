<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Functional;

use Bupy7\Doctrine\NestedSet\NestedSetRepositoryAbstract;
use Bupy7\Doctrine\NestedSet\NestedSetService;
use Bupy7\Doctrine\NestedSet\Test\Assert\Category;
use Bupy7\Doctrine\NestedSet\Test\FunctionalTestCase;

class NestedSetServiceTest extends FunctionalTestCase
{
    public function testAddCategoryAsRoot(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        $this->getNestedSetService()->add($category);

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals(1, $result->getLevel());
        $this->assertEquals($this->getNestedSetRepository()->getNextRootLeftKey() - 2, $result->getLeftKey());
        $this->assertEquals($result->getLeftKey() + 1, $result->getRightKey());
    }

    public function testAddCategoryAsChild(): void
    {
        $category = new Category();
        $category->setName('Example Test Name 1');

        /** @var Category $parentCategory */
        $parentCategory = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetService()->add($category, $parentCategory);

        /** @var Category $result */
        $result = $this->getNestedSetRepository()->findOneByName('Example Test Name 1');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getRightKey() - $result->getLeftKey());
        $this->assertEquals($parentCategory->getLevel() + 1, $result->getLevel());
        $this->assertEquals(4, $result->getLevel());
        $this->assertEquals(28, $result->getLeftKey());
        $this->assertEquals(29, $result->getRightKey());

        // first parent
        $this->assertEquals(3, $parentCategory->getLevel());
        $this->assertEquals(27, $parentCategory->getLeftKey());
        $this->assertEquals(30, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(26, $parentCategory->getLeftKey());
        $this->assertEquals(31, $parentCategory->getRightKey());

        // thirth parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(21, $parentCategory->getLeftKey());
        $this->assertEquals(36, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    public function testRemoveChildWithoutChildren(): void
    {
        /** @var Category $category */
        $category = $this->getNestedSetRepository()->find(42); // System terminals

        $this->getNestedSetService()->remove($category);

        // first parent
        $parentCategory = $this->getNestedSetRepository()->find(39); // PBX and system phones
        $this->assertEquals(2, $parentCategory->getLevel());
        $this->assertEquals(26, $parentCategory->getLeftKey());
        $this->assertEquals(27, $parentCategory->getRightKey());

        // second parent
        $parentCategory = $this->getNestedSetRepository()->find(28); // Telephony
        $this->assertEquals(1, $parentCategory->getLevel());
        $this->assertEquals(21, $parentCategory->getLeftKey());
        $this->assertEquals(32, $parentCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

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
        $this->assertEquals(1, $childCategory->getLevel());
        $this->assertEquals(21, $childCategory->getLeftKey());
        $this->assertEquals(30, $childCategory->getRightKey());

        // checking unique keys
        $this->assertTrue($this->thereAreAllUniqueKeys());
    }

    private function thereAreAllUniqueKeys(): bool
    {
        $categories = $this->getNestedSetRepository()->findAll();
        $leftKeys = [];
        $rightKeys = [];
        foreach ($categories as $category) {
            $leftKeys[] = $category->getLeftKey();
            $rightKeys[] = $category->getRightKey();
        }
        return $leftKeys === array_unique($leftKeys) && $rightKeys === array_unique($rightKeys);
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
