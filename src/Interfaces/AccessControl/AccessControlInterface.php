<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces\AccessControl;

use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;

interface AccessControlInterface
{
    /**
     * Gets whether the specified key can be retrieved.
     *
     * @param string $key
     *     The key to be retrieved.
     * @param \CodeFoundation\FlowConfig\Interfaces\EntityIdentifier|null $entity
     *     The class name of the entity associated with the key.
     *
     * @return bool
     */
    public function canGetKey(string $key, ?EntityIdentifier $entity = null): bool;

    /**
     * Gets whether the specified key can be set.
     *
     * @param string $key
     *     The key to be set.
     * @param \CodeFoundation\FlowConfig\Interfaces\EntityIdentifier|null $entityClass
     *     The class name of the entity associated with the key.
     *
     * @return bool
     */
    public function canSetKey(string $key, ?EntityIdentifier $entityClass = null): bool;
}
