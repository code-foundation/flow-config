<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\TestCases;

use CodeFoundation\FlowConfig\Config\DoctrineEntityManagerBuilder;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface;
     */
    protected $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->getEntityManager();
        $this->buildSchema($this->getEntityList());
    }

    /**
     * Build the a Doctrine Configuration map.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        if ($this->entityManager === null) {
            $connection = ['driver' => 'pdo_sqlite', 'path'   => ':memory:'];

            $config = DoctrineEntityManagerBuilder::getDoctrineConfig();
            $this->entityManager = DoctrineEntityManagerBuilder::getEntityManager($connection, $config);
        }

        return $this->entityManager;
    }

    /**
     * Create schema for passed in entities.
     *
     * @param array $entities List of entity classe paths.
     */
    private function buildSchema(array $entities): void
    {
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $classMetadataList = [];
        foreach ($entities as $entity) {
            $classMetadataList[] = $entityManager->getClassMetadata($entity);
        }
        $schemaTool->createSchema($classMetadataList);
    }

    abstract protected function getEntityList(): array;
}
