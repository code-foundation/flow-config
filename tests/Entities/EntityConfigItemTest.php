<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Entities;

use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CodeFoundation\FlowConfig\Entity\EntityConfigItem
 */
class EntityConfigItemTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $configItem = new EntityConfigItem();
        $configItem->setKey('key');
        $configItem->setEntityId('123456');
        $configItem->setEntityType('user');
        $configItem->setValue('value');

        self::assertSame('key', $configItem->getKey());
        self::assertSame('123456', $configItem->getEntityId());
        self::assertSame('user', $configItem->getEntityType());
        self::assertSame('value', $configItem->getValue());
    }

    /**
     * Test exceptions are thrown when modifying the key of a config item.
     */
    public function testExceptionWhenChangingKey(): void
    {
        $configItem = new EntityConfigItem();
        $configItem->setKey('key');

        $this->expectException(EntityKeyChangeException::class);

        $configItem->setKey('newkey');
    }

    /**
     * Test exceptions are thrown when modifying the EntityId of a config item.
     */
    public function testExceptionWhenChangingEntityId(): void
    {
        $configItem = new EntityConfigItem();
        $configItem->setEntityId('abc123');

        $this->expectException(EntityKeyChangeException::class);

        $configItem->setEntityId('xyz000');
    }

    /**
     * Test exceptions are thrown when modifying the EntityId of a config item.
     */
    public function testExceptionWhenChangingEntityType(): void
    {
        $configItem = new EntityConfigItem();
        $configItem->setEntityType('user');

        $this->expectException(EntityKeyChangeException::class);

        $configItem->setEntityType('customer');
    }
}
