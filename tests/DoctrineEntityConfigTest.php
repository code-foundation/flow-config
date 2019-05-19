<?php

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
use CodeFoundation\FlowConfig\EntityConfigRepositoryInterface;
use CodeFoundation\Entity\EntityConfigItem;
use CodeFoundation\Entity\Person;
use CodeFoundation\Entity\User;

use CodeFoundation\FlowConfig\Tests\DbSetup;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
 *
 * @covers \CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig
 */
class DoctrineEntityConfigTest extends KernelTestCase
{
    use DbSetup;

    /**
     * @var EntityManager;
     */
    private $em;

    /**
     * Build a temporary sqlite database for unit testing.
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = $this->buildTestDatabase(
            self::$container,
            '/tmp/test.sqlite',
            [EntityConfigItem::class]
        );

        parent::setUp();
    }

    /**
     * Delete the temporary sqlite database.
     */
    public function tearDown()
    {
        $this->destroyTestDatabase('/tmp/test.sqlite');
        parent::tearDown();
    }

    /**
     * Enforce setting expected responses from DoctrineEntityConfig.
     */
    public function testClassStructure()
    {
        $config = new DoctrineEntityConfig($this->em);
        $this->assertInstanceOf(EntityConfigRepositoryInterface::class, $config);
        $this->assertTrue($config->canSetByEntity());
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     */
    public function testDefaultValuesAreReturned()
    {
        $expected = 'different';

        $config = new DoctrineEntityConfig($this->em);

        $person = new Person();
        $person->setExternalUuid('lol');
        $user = new User($person);

        $config->setByEntity($user, 'somekey', 'newuservalue');

        $configNew = new DoctrineEntityConfig($this->em);

        $actualUserValue = $configNew->getByEntity(
            $user,
            'someotherkey',
            'different'
        );

        $this->assertEquals($expected, $actualUserValue);
    }

    /**
     * Test that running set() with an identity sets the value for that object.
     */
    public function testSettingValuesIsSaved()
    {
        $expected = 'newuservalue';

        $config = new DoctrineEntityConfig($this->em);

        $person = new Person();
        $person->setExternalUuid('lol');
        $user = new User($person);

        $config->setByEntity($user, 'somekey', 'newuservalue');

        $configNew = new DoctrineEntityConfig($this->em);

        $actual1 = $configNew->getByEntity($user, 'somekey', null);
        $actual2 = $configNew->getByEntity($user, 'somekey', 'default');

        $this->assertEquals($expected, $actual1);
        $this->assertEquals($expected, $actual2);
    }
}
