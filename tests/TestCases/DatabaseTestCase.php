<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\TestCases;

use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\Common\Persistence\Mapping\Driver\PHPDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface;
     */
    protected $entityManager;

    public function setUp()
    {
        parent::setUp();
        $this->entityManager = $this->getEntityManager();
    }

    function getEntityManager(): EntityManager
    {
        if ($this->entityManager === null)
        {
            $config = Setup::createConfiguration(true, null, null);
            $config->setMetadataDriverImpl(new PHPDriver(new DefaultFileLocator(
                dirname(dirname(__DIR__)) . '/src/Entity/DoctrineMaps/', '.php'
            )));
            $connectionParams = array(
                'driver' => 'pdo_sqlite',
                'path'   => '/tmp/test.db',
            );

            $this->entityManager = EntityManager::create($connectionParams, $config);
        }

        return $this->entityManager;
    }
}