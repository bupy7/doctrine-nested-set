<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

interface NestedSetServiceInterface
{
    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function append(NestedSetInterface $node, NestedSetInterface $parent = null): void;

    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function prepend(NestedSetInterface $node, NestedSetInterface $parent = null): void;

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(NestedSetInterface $node): void;
}
