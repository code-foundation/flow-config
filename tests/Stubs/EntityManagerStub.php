<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Stubs;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @coversNothing
 */
class EntityManagerStub implements EntityManagerInterface
{
    /**
     * Was the entity flushed.
     *
     * @var bool
     */
    private $flushed = false;

    /**
     * Was the entity persisted.
     *
     * @var bool
     */
    private $persisted = false;

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function clear($objectName = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function contains($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function copy($entity, $deep = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedNativeQuery($name)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedQuery($name)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createNativeQuery($sql, ResultSetMapping $rsm)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($dql = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilder()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function detach($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function find($className, $id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->flushed = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCache()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($className)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEventManager()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getExpressionBuilder()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getHydrator($hydrationMode)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFactory()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getPartialReference($entityName, $identifier)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getProxyFactory()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getReference($entityName, $id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($className)
    {
        return new RepositoryStub();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitOfWork()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasFilters()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function initializeObject($obj)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isFiltersStateClean()
    {
    }

    /**
     * Get if the entity was flushed.
     *
     * @return bool
     */
    public function isFlushed(): bool
    {
        return $this->flushed;
    }

    /**
     * {@inheritdoc}
     */
    public function isOpen()
    {
    }

    /**
     * Get if the entity was persisted.
     *
     * @return bool
     */
    public function isPersisted(): bool
    {
        return $this->persisted;
    }

    /**
     * {@inheritdoc}
     */
    public function lock($entity, $lockMode, $lockVersion = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function merge($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function newHydrator($hydrationMode)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function persist($object)
    {
        $this->persisted = true;
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function transactional($func)
    {
    }
}
