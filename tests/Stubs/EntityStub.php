<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Stubs;

use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;

class EntityStub implements EntityIdentifier
{
    /**
     * @var string Fake EntityType string.
     */
    private $entityType;

    /**
     * @var string Fake entity ID string.
     */
    private $entityId;

    /**
     * UserStub constructor.
     *
     * @param string $entityType
     * @param string $entityId
     */
    public function __construct(string $entityType, string $entityId)
    {
        $this->entityType = $entityType;
        $this->entityId = $entityId;
    }

    /**
     * Get a type string that is common to this class. Usually hard coded.
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * Get the unique ID for this entity. Normally this would be an ID field.
     *
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }
}
