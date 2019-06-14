<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Entity;

use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;

class EntityConfigItem
{
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
     * Type of entity.
     *
     * Should match EntityIdentifier:getEntityType
     *
     * @var string|null
     */
    private $entityType;

    /**
     * Type of entity.
     *
     * Should match EntityIdentifier:getEntityId
     *
     * @var string|null
     */
    private $entityId;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function setKey(string $key)
    {
        if ($this->key !== null) {
            throw new EntityKeyChangeException();
        }
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return null|string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
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
     * @return null|string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param null|string $entityId
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     */
    public function setEntityId($entityId)
    {
        if ($this->entityId !== null) {
            throw new EntityKeyChangeException();
        }
        $this->entityId = $entityId;
    }
}
