<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Accessibility;

use CodeFoundation\FlowConfig\Interfaces\Accessibility\AccessibilityInterface;

/**
 * This null class returns the default of 'true' for all check methods.
 */
class NullAccessibility implements AccessibilityInterface
{
    /**
     * {@inheritDoc}
     */
    public function canGetKey(string $key): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function canSetKey(string $key): bool
    {
        return true;
    }
}
