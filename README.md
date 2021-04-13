doctrine-nested-set
===================

[![Latest Stable Version](https://poser.pugx.org/bupy7/doctrine-nested-set/v/stable)](https://packagist.org/packages/bupy7/doctrine-nested-set)
[![Total Downloads](https://poser.pugx.org/bupy7/doctrine-nested-set/downloads)](https://packagist.org/packages/bupy7/doctrine-nested-set)
[![Latest Unstable Version](https://poser.pugx.org/bupy7/doctrine-nested-set/v/unstable)](https://packagist.org/packages/bupy7/doctrine-nested-set)
[![License](https://poser.pugx.org/bupy7/doctrine-nested-set/license)](https://packagist.org/packages/bupy7/doctrine-nested-set)
[![Build Status](https://travis-ci.org/bupy7/doctrine-nested-set.svg?branch=master)](https://travis-ci.org/bupy7/doctrine-nested-set)
[![Coverage Status](https://coveralls.io/repos/github/bupy7/doctrine-nested-set/badge.svg?branch=master)](https://coveralls.io/github/bupy7/doctrine-nested-set?branch=master)

Library for Doctrine ORM releasing [the nested set model tree](https://en.wikipedia.org/wiki/Nested_set_model).

Features
--------

- [x] Multiple roots. 
- [x] Fetching all items. 
- [x] Fething children items.
- [x] Fething descendant items.
- [x] Fething a parent item.
- [x] Fething root items.
- [x] Fething ancestor items.
- [x] Adding as last of some item.
- [x] Adding as first of some item.
- [ ] TODO: Adding after some item.
- [ ] TODO: Adding before some item.
- [x] Adding as a first root item.
- [x] Adding as a last root item.
- [x] Removing a item.
- [ ] TODO: Moving item after some item.
- [ ] TODO: Moving item before some item.
- [ ] TODO: Making as root item.
- [ ] TODO: Making as a child item of some parent item.

Install
-------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

```
$ composer require bupy7/doctrine-nested-set "*"
```

Configuration
-------------

#### Add entity

```php
use Bupy7\Doctrine\NestedSet\NestedSetInterface;

/**
 * @Entity(repositoryClass="CategoryRepository")
 */
class Category implements NestedSetInterface
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int|null
     */
    private $id;
    /**
     * @Column(type="integer", name="root_key")
     * @var int
     */
    private $rootKey = 1;
    /**
     * @Column(type="integer")
     * @var int
     */
    private $level = 1;
    /**
     * @Column(type="integer", name="left_key")
     * @var int
     */
    private $leftKey;
    /**
     * @Column(type="integer", name="right_key")
     * @var int
     */
    private $rightKey;
    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): NestedSetInterface
    {
        $this->id = $id;
        return $this;
    }
    
    public function getRootKey(): int
    {
        return $this->rootKey;
    }

    public function setRootKey(int $rootKey): NestedSetInterface
    {
        $this->rootKey = $rootKey;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): NestedSetInterface
    {
        $this->level = $level;
        return $this;
    }

    public function getLeftKey(): int
    {
        return $this->leftKey;
    }

    public function setLeftKey(int $leftKey): NestedSetInterface
    {
        $this->leftKey = $leftKey;
        return $this;
    }

    public function getRightKey(): int
    {
        return $this->rightKey;
    }

    public function setRightKey(int $rightKey): NestedSetInterface
    {
        $this->rightKey = $rightKey;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }
}
```

#### Add repository

```php
use Bupy7\Doctrine\NestedSet\NestedSetRepositoryAbstract;

class CategoryRepository extends NestedSetRepositoryAbstract
{
}
```

Usage
-----

**Tree example:**

```
- PC
- - Motherboards
- - RAM
- - - DDR3
- - - DDR4
- - CPU
- Laptops
- Tablets
```

#### Fetching all items

```php
$categories = $categoryRepository->findAll();

// var_dump($categories);
//
// - PC
// - Motherboards
// - RAM
// - DDR3
// - DDR4
// - CPU
// - Laptops
// - Tablets
```

#### Fething children items

```php
$parentCategory = $categoryRepository->findOneByName('RAM');
$children = $categoryRepository->findChildren($parentCategory);

// var_dump($children);
//
// - DDR3
// - DDR4
```

#### Fething descendant items

```php
$parentCategory = $categoryRepository->findOneByName('PC');
$descendants = $categoryRepository->findDescendants($parentCategory);

// var_dump($children);
//
// - Motherboards
// - RAM
// - DDR3
// - DDR4
// - CPU
```

#### Fething a parent item

```php
$childrenCategory = $categoryRepository->findOneByName('RAM');
$parent = $categoryRepository->findOneParent($childrenCategory);

// var_dump($parent);
//
// - PC
```

#### Fething root items

```php
$roots = $categoryRepository->findRoots();

// var_dump($parent);
//
// - PC
// - Laptops
// - Tablets
```

#### Fething ancestor items

```php
$childrenCategory = $categoryRepository->findOneByName('DDR3');
$roots = $categoryRepository->findAncestors($childrenCategory);

// var_dump($parent);
//
// - RAM
// - PC
```

#### Adding as first of some item

```php
$category = new Category();
$category->setName('DDR2');

$parentCategory = $categoryRepository->findOneByName('RAM');
$categoryRepository->prepend($category, $parentCategory);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- PC
- - Motherboards
- - RAM
- - - DDR2
- - - DDR3
- - - DDR4
- - CPU
- Laptops
- Tablets
```

#### Adding as last of some item

```php
$category = new Category();
$category->setName('LGA 1151v2');

$parentCategory = $categoryRepository->findOneByName('CPU');
$categoryRepository->append($category, $parentCategory);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- PC
- - Motherboards
- - RAM
- - - DDR3
- - - DDR4
- - CPU
- - - LGA 1151v2
- Laptops
- Tablets
```

#### Adding as a first root item

```php
$category = new Category();
$category->setName('Phones');

$categoryRepository->prepend($category);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- Phones
- PC
- - Motherboards
- - RAM
- - - DDR3
- - - DDR4
- - CPU
- Laptops
- Tablets
```

#### Adding as a last root item

```php
$category = new Category();
$category->setName('Phones');

$categoryRepository->append($category);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- PC
- - Motherboards
- - RAM
- - - DDR3
- - - DDR4
- - CPU
- Laptops
- Tablets
- Phones
```

#### Removing a item

```php
$category = $categoryRepository->findOneByName('CPU');
$categoryRepository->remove($category);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- PC
- - Motherboards
- - RAM
- - - DDR3
- - - DDR4
- Laptops
- Tablets
```

or remove with descendants:

```php
$category = $categoryRepository->findOneByName('PC');
$categoryRepository->remove($category);
$entityManager->clear(); // optional, if you need
```

Result of tree:

```
- Laptops
- Tablets
```

#### You may use transactions when it needs manually

```php
use Doctrine\DBAL\TransactionIsolationLevel;

// if you want to change isolation level
// $oldIsolationLevel = $entityManager->getConnection()->getTransactionIsolation();
// $entityManager->getConnection()->setTransactionIsolation(TransactionIsolationLevel::SERIALIZABLE);

$entityManager->beginTransaction();
try {
    $category = $categoryRepository->findOneByName('PC');
    $categoryRepository->remove($category);

    $entityManager->commit();
} catch (Exception $e) {
    $entityManager->rollback();
    throw $e;
} finally {
    // if you changed isolation level
    // $entityManager->getConnection()->setTransactionIsolation($oldIsolationLevel);
}
```

License
-------

doctrine-nested-set is released under the BSD 3-Clause License.
