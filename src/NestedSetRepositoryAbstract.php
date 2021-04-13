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
            ->orderBy('ns.rootKey')
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
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.level = :level')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel() + 1,
                'rootKey' => $entity->getRootKey(),
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
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.level > :level')
            ->orderBy('ns.leftKey')
            ->setParameters([
                'level' => $entity->getLevel(),
                'rootKey' => $entity->getRootKey(),
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
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.level = :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.rightKey')
            ->setParameters([
                'level' => $entity->getLevel() - 1,
                'rootKey' => $entity->getRootKey(),
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
            ->orderBy('ns.rootKey')
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
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.level < :level')
            ->andWhere('ns.leftKey < :leftKey')
            ->andWhere('ns.rightKey > :rightKey')
            ->orderBy('ns.leftKey', 'DESC')
            ->setParameters([
                'rootKey' => $entity->getRootKey(),
                'level' => $entity->getLevel(),
                'leftKey' => $entity->getLeftKey(),
                'rightKey' => $entity->getRightKey(),
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function append(NestedSetInterface $node, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsRoot($node);
        } else {
            $this->addAsChild($node, $parent);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function prepend(NestedSetInterface $node, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsFirstRoot($node);
        } else {
            $this->addAsFirstChild($node, $parent);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function remove(NestedSetInterface $node): void
    {
        if ($node->getRightKey() - $node->getLeftKey() === 1) {
            $this->removeOne($node);
        } else {
            $this->removeWithDescendants($node);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsRoot(NestedSetInterface $node): void
    {
        $node->setRootKey($this->getMaxRootKey() + 1)
            ->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::ROOT_LEFT_KEY)
            ->setRightKey($node->getLeftKey() + 1);

        $this->getEntityManager()->persist($node);
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsFirstRoot(NestedSetInterface $node): void
    {
        $this->shiftRootKeys(NestedSetConstant::ROOT_KEY);

        $node->setRootKey(NestedSetConstant::ROOT_KEY)
            ->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::ROOT_LEFT_KEY)
            ->setRightKey($node->getLeftKey() + 1);

        $this->getEntityManager()->persist($node);
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $this->shiftGreatestLeftKeys($parent->getRootKey(), $parent->getRightKey(), 2);
        $this->shiftGreatestRightKeys($parent->getRootKey(), $parent->getRightKey(), 2);

        $child->setRootKey($parent->getRootKey())
            ->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getRightKey())
            ->setRightKey($child->getLeftKey() + 1);

        $this->getEntityManager()->persist($child);
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsFirstChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $this->shiftGreatestLeftKeys($parent->getRootKey(), $parent->getLeftKey() + 1, 2);
        $this->shiftGreatestRightKeys($parent->getRootKey(), $parent->getLeftKey(), 2);

        $child->setRootKey($parent->getRootKey())
            ->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getLeftKey() + 1)
            ->setRightKey($child->getLeftKey() + 1);

        $this->getEntityManager()->persist($child);
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeOne(NestedSetInterface $node): void
    {
        $this->shiftGreatestLeftKeys($node->getRootKey(), $node->getRightKey(), -2);
        $this->shiftGreatestRightKeys($node->getRootKey(), $node->getRightKey(), -2);

        $this->getEntityManager()->remove($node);
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeWithDescendants(NestedSetInterface $node): void
    {
        $this->removeDescendants($node->getRootKey(), $node->getLevel());

        $diff = ($node->getRightKey() - $node->getLeftKey() + 1) * -1;
        $this->shiftGreatestLeftKeys($node->getRootKey(), $node->getRightKey(), $diff);
        $this->shiftGreatestRightKeys($node->getRootKey(), $node->getRightKey(), $diff);

        $this->getEntityManager()->remove($node);
    }

    private function getMaxRootKey(): int
    {
        return (int)$this->createQueryBuilder('ns')
            ->select('MAX(ns.rootKey)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function shiftRootKeys(int $minRootKey): void
    {
        $this->getEntityManager()->createQueryBuilder()
            ->update($this->getClassName(), 'ns')
            ->set('ns.rootKey', 'ns.rootKey + 1')
            ->where('ns.rootKey >= :minRootKey')
            ->setParameters([
                'minRootKey' => $minRootKey,
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $rootKey
     * @param int $minKey
     * @param int $shiftValue
     */
    private function shiftGreatestLeftKeys(int $rootKey, int $minKey, int $shiftValue): void
    {
        $this->getEntityManager()->createQueryBuilder()
            ->update($this->getClassName(), 'ns')
            ->set('ns.leftKey', 'ns.leftKey + :shiftValue')
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.leftKey >= :minKey')
            ->setParameters([
                'rootKey' => $rootKey,
                'minKey' => $minKey,
                'shiftValue' => $shiftValue,
            ])
            ->getQuery()
            ->execute();
    }

    private function shiftGreatestRightKeys(int $rootKey, int $minKey, int $shiftValue): void
    {
        $this->getEntityManager()->createQueryBuilder()
            ->update($this->getClassName(), 'ns')
            ->set('ns.rightKey', 'ns.rightKey + :shiftValue')
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.rightKey >= :minKey')
            ->setParameters([
                'rootKey' => $rootKey,
                'minKey' => $minKey,
                'shiftValue' => $shiftValue,
            ])
            ->getQuery()
            ->execute();
    }

    private function removeDescendants(int $rootKey, int $level): void
    {
        $this->getEntityManager()->createQueryBuilder()
            ->delete($this->getClassName(), 'ns')
            ->where('ns.rootKey = :rootKey')
            ->andWhere('ns.level > :level')
            ->setParameters([
                'rootKey' => $rootKey,
                'level' => $level,
            ])
            ->getQuery()
            ->execute();
    }
}
