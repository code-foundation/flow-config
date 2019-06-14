<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Entity;

use CodeFoundation\FlowConfig\Entity\ConfigItem;
use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CodeFoundation\FlowConfig\Entity\ConfigItem
 */
class ConfigItemTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $configItem = new ConfigItem();
        $configItem->setKey('key');
        $configItem->setValue('value');

        self::assertSame('key', $configItem->getKey());
        self::assertSame('value', $configItem->getValue());
    }

    /**
     * Test exceptions are thrown when modifying the Key of a config item.
     */
    public function testExceptionWhenChangingKey(): void
    {
        $configItem = new ConfigItem();
        $configItem->setKey('this.config');

        $this->expectException(EntityKeyChangeException::class);

        $configItem->setKey('this.config');
    }
}
