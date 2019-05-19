<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces;

/**
 * Defines contract for config repositories for entities.
 *
 * Entities must support EntityIdentifier interface.
 *
 * Keys are typically 255 characters or less.
 */
interface EntityConfigRepositoryInterface
{
    /**
     * Get the config value defined by $key.
     *
     * @param EntityIdentifier $entity
     *   Entity to retrieve the configuration value for, if available.
     * @param string                                  $key
     *   Configuration key string.
     * @param mixed                                   $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed Returns the configuration item. If it is not found, the value specified
     * Returns the configuration item. If it is not found, the value specified
     */
    public function getByEntity(EntityIdentifier $entity, string $key, $default = null);

    /**
     * Sets a config value in this repository.
     *
     * @param EntityIdentifier      $entity
     *   An optional entity to associate with $key.
     *
     * @param string                                       $key
     *   The configuration items key.
     * @param                                              $value
     *   The value to associate with $key.
     *
     * @return void
     *
     * @throws \RuntimeException
     *   Thrown if it is not possible to set values associated with this repository.
     *   Typically this means canSetByEntity() returning false was ignored.
     */
    public function setByEntity(EntityIdentifier $entity, string $key, $value);

    /**
     * Defines if the implementation can support setting values config by entity.
     *
     * @return bool
     *   TRUE if the implementation supports setting a configuration item
     *    with EntityIdentifier interfaces.
     */
    public function canSetByEntity() : bool;
}
