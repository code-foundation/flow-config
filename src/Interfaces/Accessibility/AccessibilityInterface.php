<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces\Accessibility;

interface AccessibilityInterface
{
    /**
     * Gets whether the specified key can be retrieved.
     *
     * @param string $key
     *
     * @return bool
     */
    public function canGetKey(string $key): bool;

    /**
     * Gets whether the specified key can be set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function canSetKey(string $key): bool;
}
