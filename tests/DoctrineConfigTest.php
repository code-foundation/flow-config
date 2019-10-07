<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\AccessControl\NullAccessControl;
use CodeFoundation\FlowConfig\Entity\ConfigItem;
use CodeFoundation\FlowConfig\Exceptions\ValueGetException;
use CodeFoundation\FlowConfig\Exceptions\ValueSetException;
use CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface;
use CodeFoundation\FlowConfig\Interfaces\Repository\ConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\DoctrineConfig;
use CodeFoundation\FlowConfig\Tests\Stubs\AccessControlStub;
use CodeFoundation\FlowConfig\Tests\Stubs\EntityManagerStub;
use CodeFoundation\FlowConfig\Tests\TestCases\DatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\DoctrineConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\DoctrineConfig
 */
class DoctrineConfigTest extends DatabaseTestCase
{
    /**
     * Assert that `$autoFlush` being set to `false` prevents the setter from flushing the entity.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function testAutoFlushConfigIsRespectedWhenSetToFalse(): void
    {
        $entityManager = new EntityManagerStub();

        $config = $this->getConfigInstance($entityManager, false);
        $config->set('key', 'value');

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
     */
    public function testAutoFlushConfigIsRespectedWithDefaultConfig(): void
    {
        $entityManager = new EntityManagerStub();

        $config = new DoctrineConfig($entityManager);
        $config->set('key', 'value');

        self::assertTrue($entityManager->isPersisted());
        self::assertTrue($entityManager->isFlushed());
    }

    /**
     * Tests that the getter method returns the same value as the value that was passed through the setter.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function testBasicSetGet(): void
    {
        $config = $this->getConfigInstance($this->getEntityManager(), true);

        $config->set('somekey', 'somevalue');
        $actual = $config->get('somekey');

        self::assertSame('somevalue', $actual);
    }

    /**
     * Enforce setting expected responses from DoctrineConfig.
     *
     * @return void
     */
    public function testClassStructure(): void
    {
        $config = new DoctrineConfig($this->getEntityManager());
        static::assertInstanceOf(ConfigRepositoryInterface::class, $config);
        static::assertTrue($config->canSet());
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

        $this->expectException(ValueGetException::class);
        $this->expectExceptionCode(102);
        $this->expectExceptionMessage('The value for key \'test-key\' could not be retrieved.');

        $config->get('test-key');
    }

    /**
     * Test that a missing key uses a default, if given.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function testMissingKeys(): void
    {
        $expected1 = 'abc';
        $expected2 = null;

        $config = new DoctrineConfig($this->getEntityManager());
        $config->set('key1', 'abc');

        $actual1 = $config->get('key1', 'ignoreddefault');
        $actual2 = $config->get('key2');

        self::assertSame($expected1, $actual1);
        self::assertSame($expected2, $actual2);
    }

    /**
     * Test that values are actually persisted.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function testPersistence(): void
    {
        $expected = 'abc';

        $config = $this->getConfigInstance($this->getEntityManager(), true);
        $config->set('akey', 'abc');

        $config = $this->getConfigInstance();
        $actual = $config->get('akey');

        static::assertSame($expected, $actual);
    }

    /**
     * Test that empty values are persisted.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function testPersistenceOfEmptyKeys(): void
    {
        $expected = '';

        $config = $this->getConfigInstance($this->getEntityManager(), true);
        $config->set('akey', '');

        $config = $this->getConfigInstance();
        $actual = $config->get('akey');

        self::assertSame($expected, $actual);
    }

    /**
     * Tests that the `set` method throws an exception when the accessibility to write a key evaluates to `false`.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function testSetThrowsExceptionWhenKeyNotWritable(): void
    {
        $config = $this->getConfigInstance(
            $this->getEntityManager(),
            true,
            new AccessControlStub(true, false)
        );

        $this->expectException(ValueSetException::class);
        $this->expectExceptionCode(103);
        $this->expectExceptionMessage('The value for key \'test-key\' could not be set.');

        $config->set('test-key', 'my-value');
    }

    /**
     * Gets a list of entity classes to test.
     *
     * @return string[]
     */
    protected function getEntityList(): array
    {
        return [ConfigItem::class];
    }

    /**
     * Gets a configuration instance.
     *
     * @param \Doctrine\ORM\EntityManagerInterface|null $entityManager
     * @param bool|null $autoFlush
     * @param \CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface|null $accessibility
     *
     * @return \CodeFoundation\FlowConfig\Repository\DoctrineConfig
     */
    private function getConfigInstance(
        ?EntityManagerInterface $entityManager = null,
        ?bool $autoFlush = null,
        ?AccessControlInterface $accessibility = null
    ): DoctrineConfig {
        return new DoctrineConfig(
            $entityManager ?? $this->getEntityManager(),
            $autoFlush ?? false,
            $accessibility ?? new NullAccessControl()
        );
    }
}
