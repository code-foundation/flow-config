<?php
declare(strict_types=1);

use CodeFoundation\FlowConfig\Config\DoctrineEntityManagerBuilder;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/**
 * This file is for use with the Doctrine CLI utilities.
 *
 * For example;
 * > ./vendor/bin/doctrine orm:info
 */

$connection = ['driver' => 'pdo_sqlite', 'path' => ':memory:'];

$config = DoctrineEntityManagerBuilder::getDoctrineConfig();
$entityManager = DoctrineEntityManagerBuilder::getEntityManager($connection, $config);

return ConsoleRunner::createHelperSet($entityManager);
