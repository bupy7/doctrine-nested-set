<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test;

use Bupy7\Doctrine\NestedSet\Test\Fixture\CategoryFixture;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

abstract class FunctionalTestCase extends BaseTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var bool
     */
    private static $migrationApplied = false;

    protected function setUp(): void
    {
        $this->createApplication();
        $this->applyMigrations();
        $this->applyFixtures();
    }

    protected function tearDown(): void
    {
        $this->destroyApplication();
    }

    private function createApplication(): void
    {
        $config = Setup::createXMLMetadataConfiguration([__DIR__ . '/../config/orm'], true);

        $dbParams = [
            'driverClass' => Driver::class,
            'user' => Env::getInstance()->getDbUsername(),
            'password' => Env::getInstance()->getDbPassword(),
            'dbname' => Env::getInstance()->getDbName(),
            'host' => Env::getInstance()->getDbHost(),
            'port' => Env::getInstance()->getDbPort(),
        ];

        $this->entityManager = EntityManager::create($dbParams, $config);
    }

    private function destroyApplication(): void
    {
        $this->entityManager = null;
    }

    private function applyMigrations(): void
    {
        if (!self::$migrationApplied) {
            $schemaTool = new SchemaTool($this->entityManager);

            $schemaTool->dropDatabase();
            $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

            self::$migrationApplied = true;
        }
    }

    private function applyFixtures(): void
    {
        $loader = new Loader();
        $loader->addFixture(new CategoryFixture());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);

        $executor->execute($loader->getFixtures());
    }
}
