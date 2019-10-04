<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Exceptions;

use CodeFoundation\FlowConfig\Exceptions\ValueGetException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CodeFoundation\FlowConfig\Exceptions\ValueGetException
 * @covers \CodeFoundation\FlowConfig\Exceptions\BaseException
 */
class ValueGetExceptionTest extends TestCase
{
    /**
     * Tests that on creating an instance of the exception, the code, message, and key match the expected values.
     *
     * @return void
     */
    public function testExceptionCreation(): void
    {
        $key = 'test-key';
        $message = 'The value for key \'test-key\' could not be retrieved.';

        $exception = new ValueGetException($key);

        self::assertSame(102, $exception->getCode());
        self::assertSame($message, $exception->getMessage());
        self::assertSame($key, $exception->getKey());
    }
}
