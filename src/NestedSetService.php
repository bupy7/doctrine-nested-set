<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Exception;

class NestedSetService
{
    const ROOT_LEVEL = 1;

    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var NestedSetRepositoryAbstract
     */
    protected $repository;

    public function __construct(
        EntityManager $em,
        NestedSetRepositoryAbstract $repository
    ) {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function add(NestedSetInterface $child, NestedSetInterface $parent = null): void
    {
        $oldIsoTrans = $this->em->getConnection()->getTransactionIsolation();
        $this->em->getConnection()->setTransactionIsolation(Connection::TRANSACTION_SERIALIZABLE);
        $this->em->beginTransaction();

        try {
            if ($parent === null) {
                $this->addAsRoot($child);
            } else {
                $this->addAsChild($child, $parent);
            }

            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();

            throw $e;
        } finally {
            $this->em->getConnection()->setTransactionIsolation($oldIsoTrans);
        }
    }

    public function remove(NestedSetInterface $child): void
    {
        $oldIsoTrans = $this->em->getConnection()->getTransactionIsolation();
        $this->em->getConnection()->setTransactionIsolation(Connection::TRANSACTION_SERIALIZABLE);
        $this->em->beginTransaction();

        try {
            if ($child->getRightKey() - $child->getLeftKey() === 1) {
                $this->removeOne($child);
            } else {
                $this->removeWithDescendants($child);
            }

            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();

            throw $e;
        } finally {
            $this->em->getConnection()->setTransactionIsolation($oldIsoTrans);
        }
    }

    private function addAsRoot(NestedSetInterface $child): void
    {
        $child->setLevel(self::ROOT_LEVEL)
            ->setLeftKey($this->repository->getNextRootLeftKey())
            ->setRightKey($child->getLeftKey() + 1);

        $this->em->persist($child);

        $this->em->flush();
    }

    private function addAsChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getRightKey())
            ->setRightKey($child->getLeftKey() + 1);

        $entities = $this->repository->findGreatestByKeysValue($parent->getRightKey());
        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $parent->getRightKey()) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $parent->getRightKey()) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->em->persist($child);

        $this->em->flush();
    }

    private function removeOne(NestedSetInterface $child): void
    {
        $entities = $this->repository->findGreatestByKeysValue($child->getRightKey());
        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $child->getRightKey()) {
                $entity->setLeftKey($entity->getLeftKey() - 2);
            }

            if ($entity->getRightKey() >= $child->getRightKey()) {
                $entity->setRightKey($entity->getRightKey() - 2);
            }
        }

        $this->em->remove($child);

        $this->em->flush();
    }

    private function removeWithDescendants(NestedSetInterface $child): void
    {
        $descendants = $this->repository->findDescendants($child);
        foreach ($descendants as $entity) {
            $this->em->remove($entity);
        }

        $entities = $this->repository->findGreatestByKeysValue($child->getRightKey());
        $diff = $child->getRightKey() - $child->getLeftKey() + 1;
        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $child->getRightKey()) {
                $entity->setLeftKey($entity->getLeftKey() - $diff);
            }

            if ($entity->getRightKey() >= $child->getRightKey()) {
                $entity->setRightKey($entity->getRightKey() - $diff);
            }
        }

        $this->em->remove($child);

        $this->em->flush();
    }
}
