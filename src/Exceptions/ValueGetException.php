<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Exceptions;

/**
 * An exception that is thrown when a specific key is not readable.
 */
class ValueGetException extends BaseException
{
    /**
     * Constructs a new instance of the exception.
     *
     * @param string $key The key in which the value could not be retrieved.
     */
    public function __construct(string $key)
    {
        parent::__construct(
            \sprintf('The value for key \'%s\' could not be retrieved.', $key),
            self::BASE_EXCEPTION_CODE + 2
        );

        $this->setKey($key);
    }
}
