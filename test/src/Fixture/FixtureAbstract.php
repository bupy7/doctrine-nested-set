<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

abstract class FixtureAbstract extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var string
     */
    protected $entityClass;
    /**
     * @var string|null
     */
    protected $reference;
    /**
     * @var string|null
     */
    protected $dataFile;
    /**
     * @var array
     */
    protected $items;

    public function load(ObjectManager $manager): void
    {
        if ($this->dataFile !== null) {
            $this->items = require $this->dataFile;
        }

        /** @var ClassMetadataInfo $metadata */
        $metadata = $manager->getClassMetadata($this->entityClass);
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);

        foreach ($this->items as $item) {
            $entity = $this->newInstanceOfEntity($this->entityClass, $item);

            foreach ($item as $name => $value) {
                $setValueMethodName = $this->createSetValueMethodName($name);

                if (method_exists($this, $setValueMethodName)) {
                    $this->$setValueMethodName($entity, $item, $name);
                } else {
                    $entity->$setValueMethodName($value);
                }

                if ($this->reference !== null) {
                    $this->setReference($this->reference . $item['id'], $entity);
                }
            }

            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 0;
    }

    protected function createSetValueMethodName(string $name): string
    {
        $ucfirstName = ucfirst($name);
        $setValueMethodName = 'set' . $ucfirstName;

        return $setValueMethodName;
    }

    /**
     * @param string $className
     * @param array $data
     * @return object
     */
    protected function newInstanceOfEntity(string $className, array $data)
    {
        return new $className();
    }
}
