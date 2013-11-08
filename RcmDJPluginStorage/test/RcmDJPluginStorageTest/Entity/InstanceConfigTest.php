<?php


namespace RcmDJPluginStorageTest\Entity;


use RcmDJPluginStorage\Entity\InstanceConfig;

class InstanceConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDJPluginStorage\Entity\InstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->instanceConfig = new InstanceConfig();
    }

    /**
     * @covers \RcmDJPluginStorage\Entity\InstanceConfig
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
     * @covers \RcmDJPluginStorage\Entity\InstanceConfig
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