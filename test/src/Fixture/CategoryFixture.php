<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Fixture;

use Bupy7\Doctrine\NestedSet\Test\Assert\Category;

final class CategoryFixture extends FixtureAbstract
{
    protected $entityClass = Category::class;
    protected $reference = 'category';
    protected $dataFile = __DIR__ . '/category.php';
}
