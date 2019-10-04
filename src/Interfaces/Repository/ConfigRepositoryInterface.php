<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces\Repository;

/**
 * Defines the interface for all normal repositories.
 *
 * Methods must not conflict with EntityConfigRepositoryInterface.
 *
 * Keys are typically 255 characters or less.
 */
interface ConfigRepositoryInterface extends ReadonlyConfigRepositoryInterface
{
    /**
     * Sets a config value in this repository.
     *
     * @param string $key
     *   The configuration items key.
     * @param                                              $value
     *   The value to associate with $key.
     *
     * @throws \RuntimeException
     *   Thrown if it is not possible to set values associated with this repository.
     *   Typically this means canSetByEntity() returning false was ignored.
     */
    public function set(string $key, $value);
}
