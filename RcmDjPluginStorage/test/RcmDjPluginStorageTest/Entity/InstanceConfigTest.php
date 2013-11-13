<?php


namespace RcmDjPluginStorageTest\Entity;


use RcmDjPluginStorage\Entity\InstanceConfig;

class InstanceConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDjPluginStorage\Entity\InstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->instanceConfig = new InstanceConfig();
    }

    /**
     * @covers \RcmDjPluginStorage\Entity\InstanceConfig
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
     * @covers \RcmDjPluginStorage\Entity\InstanceConfig
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