<?php


namespace RcmDoctrineJsonPluginStorageTest\Entity;

require_once __DIR__.'/../../../src/RcmDoctrineJsonPluginStorage/Entity/DoctrineJsonInstanceConfig.php';

use RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig;

class InstanceConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->instanceConfig = new DoctrineJsonInstanceConfig();
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig
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
     * @covers \RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig
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