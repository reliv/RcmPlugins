<?php


namespace RcmDoctrineJsonPluginStorageTest\Entity;

require_once __DIR__.'/../../src/RcmDoctrineJsonPluginStorage/Entity/InstanceConfig.php';

use RcmDoctrineJsonPluginStorage\Entity\InstanceConfig;

class InstanceConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDoctrineJsonPluginStorage\Entity\InstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->instanceConfig = new InstanceConfig();
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Entity\InstanceConfig
     */
    public function testSetGetInstanceId()
    {
        $instanceId = 789;
        $this->instanceConfig->setInstanceId($instanceId);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(), $instanceId
        );
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Entity\InstanceConfig
     */
    public function testSetGetInstanceConfig()
    {
        $instanceConfig = array(array('key' => 'val'));
        $this->instanceConfig->setInstanceId($instanceConfig);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(), $instanceConfig
        );
    }

} 