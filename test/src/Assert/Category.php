<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Assert;

use Bupy7\Doctrine\NestedSet\NestedSetInterface;

class Category implements NestedSetInterface
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var int
     */
    private $rootKey = 1;
    /**
     * @var int
     */
    private $level = 1;
    /**
     * @var int
     */
    private $leftKey;
    /**
     * @var int
     */
    private $rightKey;
    /**
     * @var string
     */
    private $name;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return NestedSetInterface|Category
     */
    public function setId($id): NestedSetInterface
    {
        $this->id = $id;
        return $this;
    }

    public function getRootKey(): int
    {
        return $this->rootKey;
    }

    /**
     * @param int $rootKey
     * @return NestedSetInterface|Category
     */
    public function setRootKey(int $rootKey): NestedSetInterface
    {
        $this->rootKey = $rootKey;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return NestedSetInterface|Category
     */
    public function setLevel(int $level): NestedSetInterface
    {
        $this->level = $level;
        return $this;
    }

    public function getLeftKey(): int
    {
        return $this->leftKey;
    }

    /**
     * @param int $leftKey
     * @return NestedSetInterface|Category
     */
    public function setLeftKey(int $leftKey): NestedSetInterface
    {
        $this->leftKey = $leftKey;
        return $this;
    }

    public function getRightKey(): int
    {
        return $this->rightKey;
    }

    /**
     * @param int $rightKey
     * @return NestedSetInterface|Category
     */
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
