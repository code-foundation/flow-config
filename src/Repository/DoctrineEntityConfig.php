<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Repository;

use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Interfaces\EntityConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Interfaces\EntityIdentifier;
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
     * If the setter should auto flush the config.
     *
     * @var bool
     */
    private $autoFlush;

    /**
     * EntityManager that stores EntityConfigItems.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * The repository for EntityConfigItem entities.
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $configRepository;

    /**
     * DoctrineEntityConfig constructor.
     *
     * @param EntityManager $entityManager
     *   Doctrine EntityManager that can store and retrieve EntityConfigItem.
     * @param bool|null $autoFlush
     *   Set to false if you don't want the setter to flush the config value. Defaults to true.
     */
    public function __construct(EntityManagerInterface $entityManager, ?bool $autoFlush = null)
    {
        $this->entityManager = $entityManager;
        $this->configRepository = $this->entityManager->getRepository(EntityConfigItem::class);
        $this->autoFlush = $autoFlush ?? true;
    }

    /**
     * Sets a config value in this repository.
     *
     * @param EntityIdentifier $entity
     *   An optional entity to associate with $key.
     * @param string                                  $key
     *   The configuration items key.
     * @param                                         $value
     *   The value to associate with $key.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *   Thrown if entity is not valid. Typically thrown if entity is
     *    a valid but empty object.
     */
    public function setByEntity(EntityIdentifier $entity, string $key, $value)
    {
        $existing = $this->getEntityConfigItem($key, $entity);
        if ($existing) {
            $existing->setValue($value);
            $configItem = $existing;
        } elseif ($entity == null) {
            $configItem = new EntityConfigItem();
            $configItem->setKey($key);
            $configItem->setValue($value);
        } else {
            $configItem = new EntityConfigItem();
            $configItem->setKey($key);
            $configItem->setValue($value);
            $configItem->setEntityType($entity->getEntityType());
            $configItem->setEntityId($entity->getEntityId());
        }

        $this->entityManager->persist($configItem);

        if ($this->autoFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * Get the config value defined by $key.
     *
     * @param EntityIdentifier $entity
     *   Entity to retrieve the configuration value for, if available.
     *
     * @param string                                  $key
     *   Configuration key string.
     * @param mixed                                   $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed Returns the configuration item. If it is not found, the value specified
     * Returns the configuration item. If it is not found, the value specified
     * in $default will be returned.
     */
    public function getByEntity(
        EntityIdentifier $entity,
        string $key,
        $default = null
    ) {

        $existing = $this->getEntityConfigItem($key, $entity);
        if ($existing) {
            return $existing->getValue();
        } else {
            return $default;
        }
    }

    /**
     * Defines if the implementation can support setting values config by entity.
     *
     * @return bool
     *   Always true in this implementation.
     */
    public function canSetByEntity() : bool
    {
        return true;
    }

    /**
     * Find and return a EntityConfigItem if it exists.
     *
     * @param string           $key
     * @param EntityIdentifier $entity
     *
     * @return EntityConfigItem
     *
     * @TODO: Once upgraded to PHP 7.1, set and allow nullable returns.
     */
    protected function getEntityConfigItem(string $key, EntityIdentifier $entity = null)
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
