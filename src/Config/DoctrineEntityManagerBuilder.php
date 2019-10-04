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
 *
 * @codeCoverageIgnore Entity manager builder static methods cannot be asserted for specific behaviour.
 */
class DoctrineEntityManagerBuilder
{
    /**
     * Gets a Doctrine configuration instance.
     *
     * @return \Doctrine\ORM\Configuration
     */
    public static function getDoctrineConfig(): Configuration
    {
        $path = \dirname(__DIR__, 2) . '/src/Entity/DoctrineMaps/';

        $config = Setup::createConfiguration(true);
        $config->setMetadataDriverImpl(new XmlDriver(
            new DefaultFileLocator($path, '.orm.xml')
        ));

        return $config;
    }

    /**
     * Gets an entity manager instance.
     *
     * @param mixed[] $connectionParameters
     * @param \Doctrine\ORM\Configuration $config
     *
     * @return \Doctrine\ORM\EntityManager
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getEntityManager(array $connectionParameters, Configuration $config): EntityManager
    {
        return EntityManager::create($connectionParameters, $config);
    }
}
