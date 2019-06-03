<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Functional;

use Bupy7\Doctrine\NestedSet\NestedSetRepositoryAbstract;
use Bupy7\Doctrine\NestedSet\Test\Assert\Category;
use Bupy7\Doctrine\NestedSet\Test\FunctionalTestCase;

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

    private function getNestedSetRepository(): NestedSetRepositoryAbstract
    {
        return $this->entityManager->getRepository(Category::class);
    }
}
