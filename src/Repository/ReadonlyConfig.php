<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Repository;

use CodeFoundation\FlowConfig\Interfaces\Repository\ReadonlyConfigRepositoryInterface;

/**
 * Provides a configuration that cannot altered after construction.
 */
class ReadonlyConfig implements ReadonlyConfigRepositoryInterface
{
    private $data;

    /**
     * Build a readonly configuration
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->data = $config;
    }

    /**
     * Defines if the implementation supports setting config values.
     *
     * @return bool
     *   Always false.
     */
    public function canSet(): bool
    {
        return false;
    }

    /**
     * Get configuration item from readonly repository.
     *
     * @param string $key
     *   Key of configuration item to look up.
     * @param null $default
     *   Value to return if $key is not found.
     *
     * @return mixed
     *   Configuration value if found, $default if not.
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
     * Not implemented in this repository.
     *
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value): void
    {
        throw new \InvalidArgumentException('Setting values is not possible in this repository.');
    }
}
