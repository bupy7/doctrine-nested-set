<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

use Doctrine\ORM\EntityRepository;

abstract class NestedSetRepositoryAbstract extends EntityRepository
{
    private const ROOT_LEVEL = 1;
    private const DEFAULT_ROOT_LEFT_KEY = 1;

    /**
     * @return NestedSetInterface[]|array
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('ns')
            ->orderBy('ns.leftKey')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return array
     */
    public function findChildren(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.level = :level')
            ->andWhere('ns.leftKey > :leftKey')
            ->andWhere('ns.rightKey < :rightKey')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel() + 1,
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]|array
     */
    public function findDescendants(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.level > :level')
            ->andWhere('ns.leftKey > :leftKey')
            ->andWhere('ns.rightKey < :rightKey')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel(),
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->getQuery()
            ->getResult();
    }

    public function findOneParent(NestedSetInterface $entity): ?NestedSetInterface
    {
        if ($entity->getLevel() - 1 <= 0) {
            return null;
        }
        return $this->createQueryBuilder('ns')
            ->where('ns.level = :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.rightKey')
            ->setParameters([
                'level' => $entity->getLevel() - 1,
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return NestedSetInterface[]|array
     */
    public function findRoots(): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.level = :level')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => self::ROOT_LEVEL,
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $entity
     * @return NestedSetInterface[]|array
     */
    public function findAncestors(NestedSetInterface $entity): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.level < :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.leftKey', 'DESC')
            ->setParameters([
                'level' => $entity->getLevel(),
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->getQuery()
            ->getResult();
    }

    public function getNextRootLeftKey(): int
    {
        /** @var NestedSetInterface $entity */
        $entity = $this->createQueryBuilder('ns')
            ->where('ns.level = :level')
            ->orderBy('ns.rightKey', 'DESC')
            ->setParameters([
                'level' => self::ROOT_LEVEL,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($entity !== null) {
            return $entity->getRightKey() + 1;
        }

        return self::DEFAULT_ROOT_LEFT_KEY;
    }

    /**
     * @param int $minKeyValue
     * @return NestedSetInterface[]|array
     */
    public function findGreatestByKeysValue(int $minKeyValue): array
    {
        return $this->createQueryBuilder('ns')
            ->where('ns.leftKey >= :minKeyValue')
            ->orWhere('ns.rightKey >= :minKeyValue')
            ->setParameters([
                'minKeyValue' => $minKeyValue,
            ])
            ->getQuery()
            ->getResult();
    }
}
