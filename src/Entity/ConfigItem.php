<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Entity;

use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;

/**
 * Key/Value entity. Not associated with any settings.
 */
class ConfigItem
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
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Change the key for this value.
     *
     * @param string $key
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
     * Get a setting value.
     *
     * @return string Current value for this setting.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Change the value for this setting.
     *
     * @param string $value New value for this setting.
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
