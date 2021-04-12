<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

interface NestedSetRepositoryInterface
{
    /**
     * @return NestedSetInterface[]
     */
    public function findAll(): array;

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findChildren(NestedSetInterface $entity): array;

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findDescendants(NestedSetInterface $entity): array;

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface|null
     */
    public function findOneParent(NestedSetInterface $entity): ?NestedSetInterface;

    /**
     * @return NestedSetInterface[]
     */
    public function findRoots(): array;

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findAncestors(NestedSetInterface $entity): array;

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
