<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Exceptions;

/**
 * An exception that is thrown when the value for the specified key cannot be set.
 */
class ValueSetException extends BaseException
{
    /**
     * Constructs a new instance of the exception.
     *
     * @param string $key The key in which the value could not be set.
     */
    public function __construct(string $key)
    {
        parent::__construct(
            \sprintf('The value for key \'%s\' could not be set.', $key),
            self::BASE_EXCEPTION_CODE + 3
        );
    }
}
