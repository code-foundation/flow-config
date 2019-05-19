<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig;

use CodeFoundation\Entity\ConfigItem;
use CodeFoundation\FlowConfig\Interfaces\ConfigRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Provide a configuration repository.
 */
class DoctrineConfig implements ConfigRepositoryInterface
{
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
     * @param EntityManagerInterface $entityManager
     *   Doctrine EntityManager that can store and retrieve EntityConfigItem.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->configRepository = $this->entityManager->getRepository(ConfigItem::class);
    }

    /**
     * Sets a config value in this repository.
     *
     * @param string                                       $key
     *   The configuration items key.
     * @param                                              $value
     *   The value to associate with $key.
     */
    public function set(string $key, $value)
    {
        $existing = $this->getConfigItem($key);
        if ($existing) {
            $existing->setValue($value);
            $configItem = $existing;
        } else {
            $configItem = new ConfigItem();
            $configItem->setKey($key);
            $configItem->setValue($value);
        }

        $this->entityManager->persist($configItem);
        $this->entityManager->flush();
    }

    /**
     * Get the config value defined by $key.
     *
     * @param string                                       $key
     *   Configuration key string.
     * @param mixed                                        $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed
     *   Returns the configuration item. If it is not found, the value specified
     *   in $default will be returned.
     */
    public function get(string $key, $default = null)
    {
        $existing = $this->getConfigItem($key);
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
    public function canSet() : bool
    {
        return true;
    }

    /**
     * Find and return a ConfigItem if it exists.
     *
     * @param string           $key
     *
     * @return ConfigItem
     *
     * @TODO: Once upgraded to PHP 7.1, set and allow nullable returns.
     */
    protected function getConfigItem(string $key)
    {
        return $this->configRepository->findOneBy(['key' => $key]);
    }
}
