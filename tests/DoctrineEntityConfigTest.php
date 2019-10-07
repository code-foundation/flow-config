<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\AccessControl\NullAccessControl;
use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Exceptions\ValueGetException;
use CodeFoundation\FlowConfig\Exceptions\ValueSetException;
use CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface;
use CodeFoundation\FlowConfig\Interfaces\Repository\EntityConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
use CodeFoundation\FlowConfig\Tests\Stubs\AccessControlStub;
use CodeFoundation\FlowConfig\Tests\Stubs\EntityManagerStub;
use CodeFoundation\FlowConfig\Tests\Stubs\EntityStub;
use CodeFoundation\FlowConfig\Tests\TestCases\DatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig
 */
class DoctrineEntityConfigTest extends DatabaseTestCase
{
    /**
     * Assert that `$autoFlush` being set to `false` prevents the setter from flushing the entity.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testAutoFlushConfigIsRespectedWhenSetToFalse(): void
    {
        $user = new EntityStub('user', 'USER_ID');

        $entityManager = new EntityManagerStub();
        $config = $this->getConfigInstance($entityManager, false);
        $config->setByEntity($user, 'key', 'value');

        self::assertTrue($entityManager->isPersisted());
        self::assertFalse($entityManager->isFlushed());
    }

    /**
     * Assert that default constructor config keeps auto flushing to true.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testAutoFlushConfigIsRespectedWithDefaultConfig(): void
    {
        $user = new EntityStub('user', 'USER_ID');

        $entityManager = new EntityManagerStub();
        $config = $this->getConfigInstance($entityManager, true);
        $config->setByEntity($user, 'key', 'value');

        self::assertTrue($entityManager->isPersisted());
        self::assertTrue($entityManager->isFlushed());
    }

    /**
     * Enforce setting expected responses from DoctrineEntityConfig.
     *
     * @return void
     */
    public function testClassStructure(): void
    {
        $config = new DoctrineEntityConfig($this->getEntityManager());
        self::assertInstanceOf(EntityConfigRepositoryInterface::class, $config);
        self::assertTrue($config->canSetByEntity());
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testDefaultValuesAreReturned(): void
    {
        $expected = 'different';
        $config = $this->getConfigInstance();
        $user = new EntityStub('user', 'lol');
        $config->setByEntity($user, 'somekey', 'newuservalue');
        $configNew = $this->getConfigInstance();

        $actualUserValue = $configNew->getByEntity(
            $user,
            'someotherkey',
            'different'
        );

        self::assertSame($expected, $actualUserValue);
    }

    /**
     * Tests that the `get` method throws an exception when the accessibility for a key evaluates to `false`.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     */
    public function testGetThrowsExceptionWhenKeyNotReadable(): void
    {
        $config = $this->getConfigInstance(
            $this->getEntityManager(),
            true,
            new AccessControlStub(false)
        );
        $user = new EntityStub('user', 'lol');

        $this->expectException(ValueGetException::class);
        $this->expectExceptionCode(102);
        $this->expectExceptionMessage('The value for key \'test-key\' could not be retrieved.');

        $config->getByEntity($user, 'test-key');
    }

    /**
     * Tests that the `set` method throws an exception when the accessibility to write a key evaluates to `false`.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSetThrowsExceptionWhenKeyNotWritable(): void
    {
        $config = $this->getConfigInstance(
            $this->getEntityManager(),
            true,
            new AccessControlStub(true, false)
        );
        $user = new EntityStub('user', 'lol');

        $this->expectException(ValueSetException::class);
        $this->expectExceptionCode(103);
        $this->expectExceptionMessage('The value for key \'test-key\' could not be set.');

        $config->setByEntity($user, 'test-key', 'my-value');
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSettingValuesIsSaved(): void
    {
        $expected = 'newuservalue';
        $config = $this->getConfigInstance($this->getEntityManager(), true);
        $user = new EntityStub('user', 'lol');
        $config->setByEntity($user, 'somekey', 'newuservalue');

        $configNew = $this->getConfigInstance($this->getEntityManager(), true);

        $actual1 = $configNew->getByEntity($user, 'somekey', null);
        $actual2 = $configNew->getByEntity($user, 'somekey', 'default');

        self::assertSame($expected, $actual1);
        self::assertSame($expected, $actual2);
    }

    /**
     * Gets a list of entity classes to test.
     *
     * @return string[]
     */
    protected function getEntityList(): array
    {
        return [EntityConfigItem::class];
    }

    /**
     * Gets a configuration instance.
     *
     * @param \Doctrine\ORM\EntityManagerInterface|null $entityManager
     * @param bool|null $autoFlush
     * @param \CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface|null $accessibility
     *
     * @return \CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig
     */
    private function getConfigInstance(
        ?EntityManagerInterface $entityManager = null,
        ?bool $autoFlush = null,
        ?AccessControlInterface $accessibility = null
    ): DoctrineEntityConfig {
        return new DoctrineEntityConfig(
            $entityManager ?? $this->getEntityManager(),
            $autoFlush ?? false,
            $accessibility ?? new NullAccessControl()
        );
    }
}
