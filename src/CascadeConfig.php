<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig;

use CodeFoundation\FlowConfig\Interfaces\CompositeConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;

class CascadeConfig implements CompositeConfigRepositoryInterface
{

    /**
     * Readonly or hard coded repository.
     *
     * @var ReadonlyConfigRepositoryInterface
     */
    private $readonlyRepository;

    /**
     * Config repository for system wide values.
     *
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * Config repository for configuration values associated with entities.
     *
     * @var EntityConfigRepositoryInterface
     */
    private $entityConfigRepository;

    /**
     * Build CascadeConfig object.
     *
     * Note that readonly Repository isn't written to, even if it allows it.
     *
     * @param ReadonlyConfigRepositoryInterface $readonlyRepository
     * @param ConfigRepositoryInterface         $configRepository
     * @param EntityConfigRepositoryInterface   $entityConfigRepository
     */
    public function __construct(
        ReadonlyConfigRepositoryInterface $readonlyRepository,
        ConfigRepositoryInterface $configRepository,
        EntityConfigRepositoryInterface $entityConfigRepository
    ) {
        $this->readonlyRepository = $readonlyRepository;
        $this->configRepository = $configRepository;
        $this->entityConfigRepository = $entityConfigRepository;
    }

    /**
     * Get the config value defined by $key.
     *
     * @param string $key
     *   Configuration key string.
     * @param mixed  $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed
     *   Returns the configuration item. If it is not found, the value specified
     *   in $default will be returned.
     */
    public function get(string $key, $default = null)
    {
        if ($value = $this->configRepository->get($key, null)) {
            return $value;
        } else {
            return $this->readonlyRepository->get($key, $default);
        }
    }

    /**
     * Sets a config value in this config repository.
     *
     * @param string                                       $key
     *   The configuration items key.
     * @param                                              $value
     *   The value to associate with $key.
     */
    public function set(string $key, $value)
    {
        $this->configRepository->set($key, $value);
    }

    /**
     * Get the config value defined by $key.
     *
     * @param \CodeFoundation\Entity\EntityIdentifier $entity
     *   Entity to retrieve the configuration value for, if available.
     * @param string                                  $key
     *   Configuration key string.
     * @param mixed                                   $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed Returns the configuration item. If it is not found, the value specified
     * Returns the configuration item. If it is not found, the value specified
     * in $default will be returned.
     */
    public function getByEntity(
        EntityIdentifier $entity,
        string $key,
        $default = null
    ) {
        if ($value = $this->entityConfigRepository->getByEntity(
            $entity,
            $key,
            $default
        )) {
            return $value;
        } elseif ($value = $this->configRepository->get($key, $default)) {
            return $value;
        } else {
            return $this->readonlyRepository->get($key, $default);
        }
    }

    /**
     * Sets a config value in this repository.
     *
     * @param \CodeFoundation\Entity\EntityIdentifier $entity
     *   An optional entity to associate with $key.
     * @param string                                  $key
     *   The configuration items key.
     * @param                                         $value
     *   The value to associate with $key.
     *
     * @return void
     *
     * @throws \RuntimeException
     *   Thrown if it is not possible to set values associated with this repository.
     *   Typically this means canSetByEntity() returning false was ignored.
     */
    public function setByEntity(EntityIdentifier $entity, string $key, $value)
    {
        $this->entityConfigRepository->setByEntity($entity, $key, $value);
    }

    /**
     * Defines if the implementation supports setting config values.
     *
     * @return bool
     *   Always true. Written to system config;
     */
    public function canSet() : bool
    {
        return true;
    }

    /**
     * Defines if the implementation can support setting values config by entity.
     *
     * @return bool
     *   Always true. Written to entity config;
     */
    public function canSetByEntity() : bool
    {
        return true;
    }
}
