<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet;

use Doctrine\ORM\EntityManagerInterface;

class NestedSetService implements NestedSetServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var NestedSetRepositoryInterface
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        NestedSetRepositoryInterface $repository
    ) {
        $this->em = $em;
        $this->repository = $repository;
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
        $node->setRoot($this->repository->getMaxRoot() + 1)
            ->setLevel(NestedSetConstant::ROOT_LEVEL)
            ->setLeftKey(NestedSetConstant::ROOT_LEFT_KEY)
            ->setRightKey($node->getLeftKey() + 1);

        $this->em->persist($node);

        $this->em->flush();
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

        $this->repository->shiftRoots(NestedSetConstant::ROOT_KEY);

        $this->em->persist($node);

        $this->em->flush();
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

        $entities = $this->repository->findGreatest($parent->getRoot(), $rightKey);

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
     * @throws \Doctrine\ORM\ORMException
     */
    private function addAsFirstChild(NestedSetInterface $child, NestedSetInterface $parent): void
    {
        $child->setRoot($parent->getRoot())
            ->setLevel($parent->getLevel() + 1)
            ->setLeftKey($parent->getLeftKey() + 1)
            ->setRightKey($child->getLeftKey() + 1);

        $leftKey = $parent->getLeftKey();

        $entities = $this->repository->findGreatest($parent->getRoot(), $leftKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() > $leftKey) {
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
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeOne(NestedSetInterface $node): void
    {
        $rightKey = $node->getRightKey();

        $entities = $this->repository->findGreatest($node->getRoot(), $rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - 2);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - 2);
            }
        }

        $this->em->remove($node);

        $this->em->flush();
    }

    /**
     * @param NestedSetInterface $node
     * @throws \Doctrine\ORM\ORMException
     */
    private function removeWithDescendants(NestedSetInterface $node): void
    {
        $descendants = $this->repository->findDescendants($node);
        foreach ($descendants as $entity) {
            $this->em->remove($entity);
        }

        $rightKey = $node->getRightKey();
        $diff = $rightKey - $node->getLeftKey() + 1;

        $entities = $this->repository->findGreatest($node->getRoot(), $rightKey);

        foreach ($entities as $entity) {
            if ($entity->getLeftKey() >= $rightKey) {
                $entity->setLeftKey($entity->getLeftKey() - $diff);
            }

            if ($entity->getRightKey() >= $rightKey) {
                $entity->setRightKey($entity->getRightKey() - $diff);
            }
        }

        $this->em->remove($node);

        $this->em->flush();
    }
}
