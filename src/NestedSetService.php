<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class NestedSetService
{
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

    /**
     * @TODO: Request the user transaction.
     * 
     * @param NestedSetInterface $child
     * @param NestedSetInterface|null $parent
     * @throws ORMException
     */
    public function append(NestedSetInterface $child, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsRoot($child);
        } else {
            $this->addAsChild($child, $parent);
        }
    }

    /**
     * @TODO: Request the user transaction.
     *
     * @param NestedSetInterface $child
     * @param NestedSetInterface|null $parent
     * @throws ORMException
     */
    public function prepend(NestedSetInterface $child, NestedSetInterface $parent = null): void
    {
        if ($parent === null) {
            $this->addAsFirstRoot($child);
        } else {
            $this->addAsFirstChild($child, $parent);
        }
    }

    /**
     * @TODO: Request the user transaction.
     * 
     * @param NestedSetInterface $child
     * @throws ORMException
     */
    public function remove(NestedSetInterface $child): void
    {
        if ($child->getRightKey() - $child->getLeftKey() === 1) {
            $this->removeOne($child);
        } else {
            $this->removeWithDescendants($child);
        }
    }

    /**
     * @param NestedSetInterface $child
     * @throws ORMException
     */
    private function addAsRoot(NestedSetInterface $child): void
    {
        $child->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey($this->repository->getNextRootLeftKey())
            ->setRightKey($child->getLeftKey() + 1);

        $this->em->persist($child);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @throws ORMException
     */
    private function addAsFirstRoot(NestedSetInterface $child): void
    {
        $child->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::DEFAULT_ROOT_LEFT_KEY)
            ->setRightKey($child->getLeftKey() + 1);

        $leftKey = $child->getLeftKey();

        $entities = $this->repository->findGreatestByKeysValue($leftKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $leftKey) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $leftKey) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->em->persist($child);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws ORMException
     */
    private function addAsChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getRightKey())
            ->setRightKey($child->getLeftKey() + 1);

        $rightKey = $parent->getRightKey();

        $entities = $this->repository->findGreatestByKeysValue($rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->em->persist($child);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @param NestedSetInterface $parent
     * @throws ORMException
     */
    private function addAsFirstChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getLeftKey())
            ->setRightKey($child->getLeftKey() + 1);

        $leftKey = $parent->getLeftKey();

        $entities = $this->repository->findGreatestByKeysValue($leftKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $leftKey) {
                $entity->setLeftKey($entity->getLeftKey() + 2);
            }

            if ($entity->getRightKey() >= $leftKey) {
                $entity->setRightKey($entity->getRightKey() + 2);
            }
        }

        $this->em->persist($child);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @throws ORMException
     */
    private function removeOne(NestedSetInterface $child): void
    {
        $rightKey = $child->getRightKey();

        $entities = $this->repository->findGreatestByKeysValue($rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - 2);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - 2);
            }
        }

        $this->em->remove($child);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $child
     * @throws ORMException
     */
    private function removeWithDescendants(NestedSetInterface $child): void
    {
        $descendants = $this->repository->findDescendants($child);
        foreach ($descendants as $entity) {
            $this->em->remove($entity);
        }

        $rightKey = $child->getRightKey();
        $diff = $rightKey - $child->getLeftKey() + 1;

        $entities = $this->repository->findGreatestByKeysValue($rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - $diff);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - $diff);
            }
        }

        $this->em->remove($child);

        $this->em->flush();
    }
}
