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

    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function append(NestedSetInterface $node, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsRoot($node);
        } else {
            $this->addAsChild($node, $parent);
        }
    }

    /**
     * @param NestedSetInterface $node
     * @param NestedSetInterface|null $parent
     * @throws \Doctrine\ORM\ORMException
     */
    public function prepend(NestedSetInterface $node, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsFirstRoot($node);
        } else {
            $this->addAsFirstChild($node, $parent);
        }
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(NestedSetInterface $node): void
    {
        if ($node->getRightKey() - $node->getLeftKey() === 1) {
            $this->removeOne($node);
        } else {
            $this->removeWithDescendants($node);
        }
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsRoot(NestedSetInterface $node): void
    {
        $node->setRoot($this->getMaxRoot() + 1)
            ->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::ROOT_LEFT_KEY)
            ->setRightKey($node->getLeftKey() + 1);

        $this->getEntityManager()->persist($node);

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsFirstRoot(NestedSetInterface $node): void
    {
        $node->setRoot(NestedSetConstant::ROOT_KEY)
            ->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::ROOT_LEFT_KEY)
            ->setRightKey($node->getLeftKey() + 1);

        $this->shiftRoots(NestedSetConstant::ROOT_KEY);

        $this->getEntityManager()->persist($node);

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setRoot($parent->getRoot())
            ->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getRightKey())
            ->setRightKey($child->getLeftKey() + 1);

        $rightKey = $parent->getRightKey();

        $entities = $this->findGreatest($parent->getRoot(), $rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->getEntityManager()->persist($child);

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsFirstChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setRoot($parent->getRoot())
            ->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getLeftKey() + 1)
            ->setRightKey($child->getLeftKey() + 1);

        $leftKey = $parent->getLeftKey();

        $entities = $this->findGreatest($parent->getRoot(), $leftKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() > $leftKey) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $leftKey) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->getEntityManager()->persist($child);

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeOne(NestedSetInterface $node): void
    {
        $rightKey = $node->getRightKey();

        $entities = $this->findGreatest($node->getRoot(), $rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - 2);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - 2);
            }
        }

        $this->getEntityManager()->remove($node);

        $this->getEntityManager()->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeWithDescendants(NestedSetInterface $node): void
    {
        $descendants = $this->findDescendants($node);
        foreach ($descendants as $entity) {
            $this->getEntityManager()->remove($entity);
        }

        $rightKey = $node->getRightKey();
        $diff = $rightKey - $node->getLeftKey() + 1;

        $entities = $this->findGreatest($node->getRoot(), $rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - $diff);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - $diff);
            }
        }

        $this->getEntityManager()->remove($node);

        $this->getEntityManager()->flush();
    }

    private function getMaxRoot(): int
    {
        return (int)$this->createQueryBuilder('ns')
            ->select('MAX(ns.root)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function shiftRoots(int $minRoot): void
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
    private function findGreatest(int $root, int $minKey): array
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
