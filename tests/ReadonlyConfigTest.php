<?php

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\Interfaces\Repository\ReadonlyConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\ReadonlyConfig;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\ReadonlyConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\ReadonlyConfig
 */
class ReadonlyConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test that basic get() against a known key works properly.
     */
    public function testBasicGet(): void
    {
        $expected = 'somevalue';

        $config = new ReadonlyConfig(['somekey' => 'somevalue']);

        $actual = $config->get('somekey');

        $this->assertSame($expected, $actual);
    }

    /**
     * Enforce setting expected responses from ReadonlyConfig.
     */
    public function testClassStructure(): void
    {
        $config = new ReadonlyConfig(['somekey' => 'somevalue']);
        $this->assertInstanceOf(ReadonlyConfigRepositoryInterface::class, $config);
        $this->assertFalse($config->canSet());
    }

    /**
     * Test that default values are returned when the key doesn't exist.
     */
    public function testGetDefaults(): void
    {
        $expected1 = 'somevalue';
        $expected2 = 'default2';
        $expected3 = null;

        $config = new ReadonlyConfig(['somekey' => 'somevalue']);

        $actual1 = $config->get('somekey', 'default1');
        $actual2 = $config->get('otherkey', 'default2');
        $actual3 = $config->get('anotherkey');

        $this->assertSame($expected1, $actual1);
        $this->assertSame($expected2, $actual2);
        $this->assertSame($expected3, $actual3);
    }

    /**
     * Test that an exception is thrown when trying to set a value.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Setting values is not possible in this repository.
     */
    public function testSetValueException(): void
    {
        $config = new ReadonlyConfig(['somekey' => 'somevalue']);
        $config->set('anykey', 'somevalue');
    }
}
