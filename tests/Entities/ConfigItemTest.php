<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Entity;

use CodeFoundation\FlowConfig\Entity\ConfigItem;
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
}
