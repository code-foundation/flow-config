<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Stubs;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * @coversNothing
 */
class RepositoryStub implements ObjectRepository
{
    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'TestType';
    }
}
