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
}
