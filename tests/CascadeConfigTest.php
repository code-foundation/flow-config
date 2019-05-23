<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests;

use CodeFoundation\FlowConfig\Entity\ConfigItem;
use CodeFoundation\FlowConfig\Entity\EntityConfigItem;
use CodeFoundation\FlowConfig\Repository\CascadeConfig;
use CodeFoundation\FlowConfig\Interfaces\CompositeConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Interfaces\ConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\DoctrineConfig;
use CodeFoundation\FlowConfig\Repository\DoctrineEntityConfig;
use CodeFoundation\FlowConfig\Interfaces\EntityConfigRepositoryInterface;
use CodeFoundation\FlowConfig\Repository\ReadonlyConfig;
use CodeFoundation\FlowConfig\Tests\TestCases\DatabaseTestCase;

/**
 * Tests for CodeFoundation\FlowConfig\Repository\CascadeConfig class.
 *
 * @covers \CodeFoundation\FlowConfig\Repository\CascadeConfig
 */
class CascadeConfigTest extends DatabaseTestCase
{
    protected function getEntityList(): array
    {
        return [EntityConfigItem::class, ConfigItem::class];
    }

    /**
     * Create new CascadeConfig.
     *
     * Note that this builds new implementation each time to ensure the implementation
     *  does not just cache config values in memory.
     *
     * @return CascadeConfig
     *   Configure CascadeConfig.
     */
    private function buildCascadeConfig()
    {
        $roConfig = new ReadonlyConfig([
            'defaultkey1' => 'defaultvalue1',
            'defaultkey2' => 'defaultvalue2',
            'defaultkey3' => 'defaultvalue3',
        ]);
        $systemConfig = new DoctrineConfig($this->getEntityManager());
        $entityConfig = new DoctrineEntityConfig($this->getEntityManager());

        $config = new CascadeConfig($roConfig, $systemConfig, $entityConfig);

        return $config;
    }

    /**
     * Enforce setting expected responses from DoctrineEntityConfig.
     */
    public function testClassStructure()
    {
        $config = $this->buildCascadeConfig();
        $this->assertInstanceOf(ConfigRepositoryInterface::class, $config);
        $this->assertInstanceOf(EntityConfigRepositoryInterface::class, $config);
        $this->assertInstanceOf(CompositeConfigRepositoryInterface::class, $config);
        $this->assertTrue($config->canSet());
        $this->assertTrue($config->canSetByEntity());
    }

    /**
     * Test basic setting of values is persistent.
     */
    public function testBasicSetGet()
    {
        $expected = 'somevalue';

        $config = $this->buildCascadeConfig();
        $config->set('somekey', 'somevalue');

        $config2 = $this->buildCascadeConfig();
        $actual = $config2->get('somekey');

        $this->assertSame($expected, $actual);
    }

    /**
     * Test basic setting of values is persistent.
     */
    public function testBasicSetEmptyKeys()
    {
        $expected = null;

        $config = $this->buildCascadeConfig();
        $config->set('somekey', 'somevalue');

        $config2 = $this->buildCascadeConfig();
        $config2->set('somekey', '');

        $config2 = $this->buildCascadeConfig();
        $actual = $config2->get('somekey');

        $this->assertSame($expected, $actual);
    }

    /**
     * Test that site config overrides the vendor configuration.
     */
    public function testCascadeConfigValues()
    {
        $expected1 = 'defaultvalue1';
        $expected2 = 'newkey1';
        $expected3 = 'newkey1';

        $config = $this->buildCascadeConfig();
        $actual1 = $config->get('defaultkey1');

        $config->set('defaultkey1', 'newkey1');
        $actual2 = $config->get('defaultkey1');

        $config = $this->buildCascadeConfig();
        $actual3 = $config->get('defaultkey1');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
    }

    /**
     * Test that defaults get used.
     */
    public function testMissingKeys()
    {
        $expected1 = null;
        $expected2 = 'xyz';

        $config = $this->buildCascadeConfig();

        $actual1 = $config->get('key1');
        $actual2 = $config->get('key1', 'xyz');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }

    /**
     * Test that setting and getting a entity configuration item sets it.
     */
    public function testUserSettingValues()
    {
        $expected = 'abc';

        $config = $this->buildCascadeConfig();

        $person = new Person();
        $person->setExternalUuid('abc');
        $user = new User($person);

        $config->setByEntity($user, 'somekey', 'abc');

        $config2 = $this->buildCascadeConfig();
        $actual = $config2->getByEntity($user, 'somekey', null);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test that requesting a config value for an entity falls through to
     *  the readonly config.
     */
    public function testEntityValuesCascadeToRoConfig()
    {
        $expected = 'defaultvalue2';

        $config = $this->buildCascadeConfig();

        $person = new Person();
        $person->setExternalUuid('aah');
        $user = new User($person);

        $config2 = $this->buildCascadeConfig();
        $actual = $config2->getByEntity($user, 'defaultkey2', null);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test that requesting a config value for an entity falls through to
     *  the system config.
     */
    public function testEntityValuesCascadeToSystemConfig()
    {
        $expected = 'newvalue';

        $config = $this->buildCascadeConfig();
        $config->set('defaultkey3', 'newvalue');

        $person = new Person();
        $person->setExternalUuid('aah');
        $user = new User($person);

        $config2 = $this->buildCascadeConfig();
        $actual = $config2->getByEntity($user, 'defaultkey3', null);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test that requesting a config value for an entity falls through to
     *  the system config.
     */
    public function testEntityDefaultsAreHonoured()
    {
        $expected = 'defaultvalueabc';

        $config = $this->buildCascadeConfig();

        $person = new Person();
        $person->setExternalUuid('hoho');
        $user = new User($person);

        $actual = $config->getByEntity($user, 'defaultkey3', 'defaultvalueabc');

        $this->assertEquals($expected, $actual);
    }
}
