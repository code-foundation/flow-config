<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Config;

use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Tools\Setup;

/**
 * Build a simple Configuration map for use with Doctrine.
 */
class DoctrineEntityManagerBuilder
{
    public static function getDoctrineConfig(): Configuration
    {
        $path = dirname(dirname(__DIR__)) . '/src/Entity/DoctrineMaps/';
        $config = Setup::createConfiguration(true, null, null);
        $config->setMetadataDriverImpl(new XmlDriver(
            new DefaultFileLocator($path, '.orm.xml')
        ));
        return $config;
    }

    public static function getEntityManager(array $connectionParameters, Configuration $config): EntityManager
    {
        return EntityManager::create($connectionParameters, $config);
    }
}
