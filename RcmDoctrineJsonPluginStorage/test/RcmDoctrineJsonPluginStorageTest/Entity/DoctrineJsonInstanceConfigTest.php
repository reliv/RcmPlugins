<?php

namespace RcmDoctrineJsonPluginStorageTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

use RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig;
use RcmTest\Base\BaseTestCase;

class InstanceConfigTest extends BaseTestCase
{
    /** @var  \RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->addModule('RcmDoctrineJsonPluginStorage');
        parent::setUp();
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