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

    public function getMaxRoot(): int;

    public function shiftRoots(int $minRoot): void;

    /**
     * @param int $root
     * @param int $minKey
     * @return NestedSetInterface[]
     */
    public function findGreatest(int $root, int $minKey): array;
}
