<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

interface NestedSetServiceInterface
{
    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function append(NestedSetInterface $child, NestedSetInterface $parent = null): void;

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function prepend(NestedSetInterface $child, NestedSetInterface $parent = null): void;

    /**
     * @param NestedSetInterface $child
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(NestedSetInterface $child): void;
}
