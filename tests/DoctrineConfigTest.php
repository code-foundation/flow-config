<?php

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\Interfaces\ConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\DoctrineConfig;
use CodeFoundation\FlowConfig\Tests\TestCases\DatabaseTestCase;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\DoctrineConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\DoctrineConfig
 */
class DoctrineConfigTest extends DatabaseTestCase
{
    /**
     * Enforce setting expected responses from DoctrineConfig.
     */
    public function testClassStructure()
    {
        $config = new DoctrineConfig($this->getEntityManager());
        $this->assertInstanceOf(ConfigRepositoryInterface::class, $config);
        $this->assertTrue($config->canSet());
    }

    public function testBasicSetGet()
    {
        $config = new DoctrineConfig($this->getEntityManager());

        $config->set('somekey', 'somevalue');

        $actual = $config->get('somekey');

        $this->assertSame('somevalue', $actual);
    }

    /**
     * Test that a missing key uses a default, if given.
     */
    public function testMissingKeys()
    {
        $expected1 = 'abc';
        $expected2 = null;

        $config = new DoctrineConfig($this->getEntityManager());
        $config->set('key1', 'abc');

        $actual1 = $config->get('key1', 'ignoreddefault');
        $actual2 = $config->get('key2');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }

    /**
     * Test that values are actually persisted.
     */
    public function testPersistence()
    {
        $expected = 'abc';

        $config = new DoctrineConfig($this->getEntityManager());
        $config->set('akey', 'abc');

        $config = new DoctrineConfig($this->getEntityManager());
        $actual = $config->get('akey');

        $this->assertEquals($expected, $actual);
    }
    /**
     * Test that empty values are persisted.
     */
    public function testPersistenceOfEmptyKeys()
    {
        $expected = '';

        $config = new DoctrineConfig($this->getEntityManager());
        $config->set('akey', '');

        $config = new DoctrineConfig($this->getEntityManager());
        $actual = $config->get('akey');

        $this->assertEquals($expected, $actual);
    }
}
