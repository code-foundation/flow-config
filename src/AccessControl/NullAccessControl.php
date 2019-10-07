<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\AccessControl;

use CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface;
use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;

/**
 * This null class returns the default of 'true' for all check methods.
 */
class NullAccessControl implements AccessControlInterface
{
    /**
     * {@inheritDoc}
     */
    public function canGetKey(string $key, ?EntityIdentifier $entity = null): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function canSetKey(string $key, ?EntityIdentifier $entity = null): bool
    {
        return true;
    }
}
