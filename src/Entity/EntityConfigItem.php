<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Entity;

use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;

class EntityConfigItem
{
    /**
     * The entity id.
     *
     * Should match EntityIdentifier:getEntityId
     *
     * @var string|null
     */
    private $entityId;

    /**
     * Type of entity.
     *
     * Should match EntityIdentifier:getEntityType
     *
     * @var string|null
     */
    private $entityType;

    /**
     * Configuration key.
     *
     * @var string
     */
    private $key;

    /**
     * Value of this key.
     *
     * @var string
     */
    private $value;

    /**
     * Gets the entity id for the configuration item.
     *
     * @return null|string
     */
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * Gets the entity type for the configuration item.
     *
     * @return null|string
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the entity id for the configuration item.
     *
     * @param null|string $entityId
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function setEntityId($entityId): void
    {
        if ($this->entityId !== null) {
            throw new EntityKeyChangeException();
        }
        $this->entityId = $entityId;
    }

    /**
     * Sets the entity type for the configuration item.
     *
     * @param null|string $entityType
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function setEntityType($entityType)
    {
        if ($this->entityType !== null) {
            throw new EntityKeyChangeException();
        }
        $this->entityType = $entityType;
    }

    /**
     * Sets the key for the configuration item.
     *
     * @param string $key
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function setKey(string $key): void
    {
        if ($this->key !== null) {
            throw new EntityKeyChangeException();
        }
        $this->key = $key;
    }

    /**
     * Sets the value for the configuration item.
     *
     * @param string $value
     *
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
