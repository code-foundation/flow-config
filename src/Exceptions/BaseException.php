<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Exceptions;

use Throwable;

/**
 * Acts as a base exception for the library.
 */
abstract class BaseException extends \Exception
{
    /**
     * The base exception code.
     *
     * @const int
     */
    public const BASE_EXCEPTION_CODE = 100;

    /**
     * The related configuration key.
     *
     * @var string|null
     */
    protected $key;

    /**
     * Constructs a new instance of the BaseException class.
     *
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code ?? self::BASE_EXCEPTION_CODE, $previous);
    }

    /**
     * Gets the configuration key that this exception relates to.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the configuration key that this exception relates to.
     *
     * @param string|null $key
     *
     * @return void
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }
}
