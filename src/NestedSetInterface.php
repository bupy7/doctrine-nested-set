<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

interface NestedSetInterface
{
    /**
     * @param mixed $id
     * @return NestedSetInterface
     */
    public function setId($id): NestedSetInterface;

    /**
     * @return mixed
     */
    public function getId();

    public function getRoot(): int;

    public function setRoot(int $root): NestedSetInterface;

    public function setLevel(int $level): NestedSetInterface;

    public function getLevel(): int;

    public function setLeftKey(int $leftKey): NestedSetInterface;

    public function getLeftKey(): int;

    public function setRightKey(int $rightKey): NestedSetInterface;

    public function getRightKey(): int;
}
