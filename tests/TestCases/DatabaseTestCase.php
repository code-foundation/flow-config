<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\TestCases;

use CodeFoundation\FlowConfig\Config\DoctrineEntityManagerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
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
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
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
     * @param string[] $entities List of entity classe paths.
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

    /**
     * Get a list of object types to create in the database.
     *
     * @return string[]
     */
    abstract protected function getEntityList(): array;
}
