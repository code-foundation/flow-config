<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces\Accessibility;

interface AccessibilityInterface
{
    /**
     * They key is hidden from bulk gets by default, but can be specifically requested.
     */
    public const ACCESSIBILITY_HIDDEN = 0;

    /**
     * The key is used internally and never returned in bulk gets.
     */
    public const ACCESSIBILITY_INTERNAL = 1;

    /**
     * They key is read-only, and cannot be changed.
     */
    public const ACCESSIBILITY_READONLY = 2;

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
