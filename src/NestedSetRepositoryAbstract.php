<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

use Doctrine\ORM\EntityRepository;

abstract class NestedSetRepositoryAbstract extends EntityRepository implements NestedSetRepositoryInterface
{
    /**
     * @return NestedSetInterface[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('ns')
            ->orderBy('ns.root')
            ->addOrderBy('ns.leftKey')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findChildren(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.root = :root')
            ->andWhere('ns.level = :level')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel() + 1,
                'root' => $entity->getRoot(),
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findDescendants(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.root = :root')
            ->andWhere('ns.level > :level')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel(),
                'root' => $entity->getRoot(),
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface|null
     */
    public function findOneParent(NestedSetInterface $entity): ?NestedSetInterface
    {
        if ($entity->getLevel() - 1 <= 0) {
            return null;
        }
        return $this->createQueryBuilder('ns')
            ->where('ns.root = :root')
            ->andWhere('ns.level = :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.rightKey')
            ->setParameters([
                'level' => $entity->getLevel() - 1,
                'root' => $entity->getRoot(),
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return NestedSetInterface[]
     */
    public function findRoots(): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.level = :level')
            ->orderBy('ns.root')
            ->addOrderBy('ns.leftKey')
            ->setParameters([
                'level' => NestedSetConstant::ROOT_LEVEL,
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]
     */
    public function findAncestors(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.root = :root')
            ->andWhere('ns.level < :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.leftKey', 'DESC')
            ->setParameters([
                'root' => $entity->getRoot(),
                'level' => $entity->getLevel(),
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->getQuery()
            ->getResult();
    }

    public function getMaxRoot(): int
    {
        return (int)$this->createQueryBuilder('ns')
            ->select('MAX(ns.root)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function shiftRoots(int $minRoot): void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update($this->getClassName(), 'ns')
            ->set('ns.root', 'ns.root + 1')
            ->where('ns.root >= :minRoot')
            ->setParameters([
                'root' => $minRoot,
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $root
     * @param int $minKey
     * @return NestedSetInterface[]
     */
    public function findGreatest(int $root, int $minKey): array
    {
        $qb = $this->createQueryBuilder('ns');
        return $qb->where('ns.root = :root')
            ->andWhere($qb->expr()->orX(
                'ns.leftKey >= :minKey',
                'ns.rightKey >= :minKey'
            ))
            ->setParameters([
                'root' => $root,
                'minKey' => $minKey,
            ])
            ->getQuery()
            ->getResult();
    }
}
