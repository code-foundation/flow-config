<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Interfaces;

/**
 * Implement interface to require objects return identifying strings.
 */
interface EntityIdentifier
{
    /**
     * Get the type of this object.
     *
     * Note that these are not class names, but short descriptive names. Values
     *  returned by this interface must be unique to the class.
     *  Recommended responses are "user", "endpoint", etc.
     *  The returned name should be lowercase and not include punctuation.
     *
     * @return string
     *   Type of object.
     */
    public function getEntityType() : string;

    /**
     * Get the unique identifier for this type.
     *
     * This is a free form string, and can be anything that along with the
     *  type return by getEntityType() uniquely identifies the object in the
     *  system. Recommended values are database primary keys, and UUIDs.
     *
     * @return string
     *   Unique identifier for this type.
     */
    public function getEntityId() : string;
}
