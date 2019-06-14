<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Entity;

use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
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
}
