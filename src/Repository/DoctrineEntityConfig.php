<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Repository;

use CodeFoundation\FlowConfig\AccessControl\NullAccessControl;
use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Exceptions\ValueGetException;
use CodeFoundation\FlowConfig\Exceptions\ValueSetException;
use CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface;
use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;
use CodeFoundation\FlowConfig\Interfaces\Repository\EntityConfigRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Provide a configuration that return the configuration for a given entity.
 *
 * If the user does not have a configuration, then it will fall back to
 *  site, then vendor configuration.
 */
class DoctrineEntityConfig implements EntityConfigRepositoryInterface
{
    /**
     * The access control instance used to determine readability and writability of keys.
     *
     * @var \CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface|null
     */
    private $accessControl;

    /**
     * If the setter should auto flush the config.
     *
     * @var bool
     */
    private $autoFlush;

    /**
     * The repository for EntityConfigItem entities.
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $configRepository;

    /**
     * EntityManager that stores EntityConfigItems.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineEntityConfig constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *   Doctrine EntityManager that can store and retrieve EntityConfigItem.
     * @param bool $autoFlush
     *   Set to false if you don't want the setter to flush the config value. Defaults to true.
     * @param AccessControlInterface $accessControl
     *   The access control instance used to determine whether keys can be retrieved or set.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        bool $autoFlush = true,
        ?AccessControlInterface $accessControl = null
    ) {
        $this->entityManager = $entityManager;
        $this->configRepository = $this->entityManager->getRepository(EntityConfigItem::class);
        $this->autoFlush = $autoFlush;
        $this->accessControl = $accessControl ?? new NullAccessControl();
    }

    /**
     * Defines if the implementation can support setting values config by entity.
     *
     * @return bool
     *   Always true in this implementation.
     */
    public function canSetByEntity(): bool
    {
        return true;
    }

    /**
     * Get the config value defined by $key.
     *
     * @param EntityIdentifier $entity
     *   Entity to retrieve the configuration value for, if available.
     *
     * @param string $key
     *   Configuration key string.
     * @param mixed $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed Returns the configuration item. If it is not found, the value specified
     * Returns the configuration item. If it is not found, the value specified
     * in $default will be returned.
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     */
    public function getByEntity(
        EntityIdentifier $entity,
        string $key,
        $default = null
    ) {
        if ($this->accessControl->canGetKey($key, $entity) === false) {
            throw new ValueGetException($key);
        }

        $existing = $this->getEntityConfigItem($key, $entity);
        if (($existing instanceof EntityConfigItem) === true) {
            return $existing->getValue();
        }

        return $default;
    }

    /**
     * Sets a config value in this repository.
     *
     * @param EntityIdentifier $entity
     *   An optional entity to associate with $key.
     * @param string $key
     *   The configuration items key.
     * @param                                         $value
     *   The value to associate with $key.
     *
     * @return void
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setByEntity(EntityIdentifier $entity, string $key, $value): void
    {
        if ($this->accessControl->canSetKey($key, $entity) === false) {
            throw new ValueSetException($key);
        }

        $configItem = $this->getEntityConfigItem($key, $entity);
        if (($configItem instanceof EntityConfigItem) === false) {
            $configItem = new EntityConfigItem();
            $configItem->setKey($key);
            $configItem->setValue($value);

            if (($entity instanceof EntityIdentifier) === true) {
                $configItem->setEntityId($entity->getEntityId());
                $configItem->setEntityType($entity->getEntityType());
            }
        }

        $configItem->setValue($value);
        $this->entityManager->persist($configItem);

        if ($this->autoFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * Find and return a EntityConfigItem if it exists.
     *
     * @param string $key
     * @param EntityIdentifier $entity
     *
     * @return EntityConfigItem|null
     */
    protected function getEntityConfigItem(string $key, EntityIdentifier $entity = null): ?EntityConfigItem
    {
        $criteria = [
            'key' => $key,
            'entityType' => null,
            'entityId' => null,
        ];

        if ($entity) {
            $criteria['entityType'] = $entity->getEntityType();
            $criteria['entityId'] = $entity->getEntityId();
        }

        return $this->configRepository->findOneBy($criteria);
    }
}
