<?php

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Interfaces\EntityConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
use CodeFoundation\FlowConfig\Tests\Stubs\EntityManagerStub;
use CodeFoundation\FlowConfig\Tests\Stubs\EntityStub;
use CodeFoundation\FlowConfig\Tests\TestCases\DatabaseTestCase;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig
 */
class DoctrineEntityConfigTest extends DatabaseTestCase
{
    protected function getEntityList(): array
    {
        return [EntityConfigItem::class];
    }

    /**
     * Enforce setting expected responses from DoctrineEntityConfig.
     */
    public function testClassStructure()
    {
        $config = new DoctrineEntityConfig($this->getEntityManager());
        $this->assertInstanceOf(EntityConfigRepositoryInterface::class, $config);
        $this->assertTrue($config->canSetByEntity());
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     */
    public function testDefaultValuesAreReturned()
    {
        $expected = 'different';
        $config = new DoctrineEntityConfig($this->getEntityManager());
        $user = new EntityStub('user', 'lol');
        $config->setByEntity($user, 'somekey', 'newuservalue');
        $configNew = new DoctrineEntityConfig($this->getEntityManager());

        $actualUserValue = $configNew->getByEntity(
            $user,
            'someotherkey',
            'different'
        );

        $this->assertEquals($expected, $actualUserValue);
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     */
    public function testSettingValuesIsSaved()
    {
        $expected = 'newuservalue';
        $config = new DoctrineEntityConfig($this->getEntityManager());
        $user = new EntityStub('user', 'lol');
        $config->setByEntity($user, 'somekey', 'newuservalue');
        $configNew = new DoctrineEntityConfig($this->getEntityManager());

        $actual1 = $configNew->getByEntity($user, 'somekey', null);
        $actual2 = $configNew->getByEntity($user, 'somekey', 'default');

        $this->assertEquals($expected, $actual1);
        $this->assertEquals($expected, $actual2);
    }

    /**
     * Assert that $autoFlush = false keeps the setter away from flushing the entity.
     *
     * @return void
     */
    public function testAutoFlushConfigIsRespectedWhenSetToFalse(): void
    {
        $user = new EntityStub('user', 'USER_ID');

        $entityManager = new EntityManagerStub();
        $config = new DoctrineEntityConfig($entityManager, false);
        $config->setByEntity($user, 'key', 'value');

        self::assertTrue($entityManager->isPersisted());
        self::assertFalse($entityManager->isFlushed());
    }

    /**
     * Assert that default constructor config keeps auto flushing to true.
     *
     * @return void
     */
    public function testAutoFlushConfigIsRespectedWithDefaultConfig(): void
    {
        $user = new EntityStub('user', 'USER_ID');

        $entityManager = new EntityManagerStub();
        $config = new DoctrineEntityConfig($entityManager);
        $config->setByEntity($user, 'key', 'value');

        self::assertTrue($entityManager->isPersisted());
        self::assertTrue($entityManager->isFlushed());
    }
}
