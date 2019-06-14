<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Entity;

use CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException;

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
}
