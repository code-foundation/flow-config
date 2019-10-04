<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Exceptions;

use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
 * @covers \CodeFoundation\FlowConfig\Exceptions\BaseException
 */
class EntityKeyChangeExceptionTest extends TestCase
{
    /**
     * Tests that on creating an instance of the exception, the code, message, and key match the expected values.
     *
     * @return void
     */
    public function testExceptionCreation(): void
    {
        $key = 'test-key';
        $message = 'The key \'test-key\' has already been set, and therefore cannot be set again.';

        $exception = new EntityKeyChangeException($key);

        self::assertSame(101, $exception->getCode());
        self::assertSame($message, $exception->getMessage());
        self::assertSame($key, $exception->getKey());
    }

    /**
     * Tests that the exception message matches the expected default when no key is provided via the constructor.
     *
     * @return void
     */
    public function testExceptionMessageWhenNoKeyProvided(): void
    {
        $message = 'This key has already been set, and therefore cannot be set again.';

        $exception = new EntityKeyChangeException();

        self::assertSame($message, $exception->getMessage());
    }
}
