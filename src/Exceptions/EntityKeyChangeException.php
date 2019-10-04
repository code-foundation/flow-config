<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Exceptions;

/**
 * This exception is thrown when the keys of an existing entity are modified.
 */
class EntityKeyChangeException extends BaseException
{
    /**
     * Constructs a new instance of the exception.
     *
     * @param string|null $key The key that could not be changed.
     */
    public function __construct(?string $key = null)
    {
        $message = $key === null
            ? 'This key has already been set, and therefore cannot be set again.'
            : \sprintf('The key \'%s\' has already been set, and therefore cannot be set again.', $key);
        $this->setKey($key);

        parent::__construct($message, self::BASE_EXCEPTION_CODE + 1);
    }
}
