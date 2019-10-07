<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Repository;

use CodeFoundation\FlowConfig\AccessControl\NullAccessControl;
use CodeFoundation\FlowConfig\Entity\ConfigItem;
use CodeFoundation\FlowConfig\Exceptions\ValueGetException;
use CodeFoundation\FlowConfig\Exceptions\ValueSetException;
use CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface;
use CodeFoundation\FlowConfig\Interfaces\Repository\ConfigRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Provide a configuration repository.
 */
class DoctrineConfig implements ConfigRepositoryInterface
{
    /**
     * The access control instance used to determine readability and writability of keys.
     *
     * @var \CodeFoundation\FlowConfig\Interfaces\AccessControl\AccessControlInterface
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
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineEntityConfig constructor.
     *
     * @param EntityManagerInterface $entityManager
     *   Doctrine EntityManager that can store and retrieve EntityConfigItem.
     * @param bool $autoFlush
     *   Set to false if you don't want the setter to flush the config value. Defaults to true.
     * @param AccessControlInterface $accessControl
     *   The access control instance used to determine whether keys are readable, or writable.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        bool $autoFlush = true,
        ?AccessControlInterface $accessControl = null
    ) {
        $this->entityManager = $entityManager;
        $this->configRepository = $this->entityManager->getRepository(ConfigItem::class);
        $this->autoFlush = $autoFlush;
        $this->accessControl = $accessControl ?? new NullAccessControl();
    }

    /**
     * Defines if the implementation can support setting values config by entity.
     *
     * @return bool
     *   Always true in this implementation.
     */
    public function canSet(): bool
    {
        return true;
    }

    /**
     * Get the config value defined by $key.
     *
     * @param string $key
     *   Configuration key string.
     * @param mixed $default
     *   Default to return if configuration key is not found. Default to null.
     *
     * @return mixed
     *   Returns the configuration item. If it is not found, the value specified
     *   in $default will be returned.
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueGetException
     */
    public function get(string $key, $default = null)
    {
        if ($this->accessControl->canGetKey($key) === false) {
            throw new ValueGetException($key);
        }

        $existing = $this->getConfigItem($key);
        if (($existing instanceof ConfigItem) === true) {
            return $existing->getValue();
        }

        return $default;
    }

    /**
     * Sets a config value in this repository.
     *
     * @param string $key
     *   The configuration items key.
     * @param                                              $value
     *   The value to associate with $key.
     *
     * @throws \CodeFoundation\FlowConfig\Exceptions\EntityKeyChangeException
     * @throws \CodeFoundation\FlowConfig\Exceptions\ValueSetException
     */
    public function set(string $key, $value): void
    {
        if ($this->accessControl->canSetKey($key) === false) {
            throw new ValueSetException($key);
        }

        $configItem = $this->getConfigItem($key);
        if (($configItem instanceof ConfigItem) === false) {
            $configItem = new ConfigItem();
            $configItem->setKey($key);
        }

        $configItem->setValue($value);

        $this->entityManager->persist($configItem);

        if ($this->autoFlush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * Find and return a ConfigItem if it exists.
     *
     * @param string $key
     *
     * @return ConfigItem|null
     */
    protected function getConfigItem(string $key): ?ConfigItem
    {
        return $this->configRepository->findOneBy(['key' => $key]);
    }
}
