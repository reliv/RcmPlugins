<?php

namespace RcmInstanceConfigTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

use RcmInstanceConfig\Entity\InstanceConfig;
use RcmTest\Base\BaseTestCase;

class InstanceConfigTest extends BaseTestCase
{
    /** @var  \RcmInstanceConfig\Entity\InstanceConfig */
    protected $instanceConfig;

    public function setUp()
    {
        $this->addModule('RcmInstanceConfig');
        parent::setUp();
        $this->instanceConfig = new InstanceConfig();
    }

    /**
     * @covers \RcmInstanceConfig\Entity\InstanceConfig
     */
    public function testSetGetInstanceId()
    {
        $instanceId = 789;
        $this->instanceConfig->setInstanceId($instanceId);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(),
            $instanceId
        );
    }

    /**
     * @covers \RcmInstanceConfig\Entity\InstanceConfig
     */
    public function testSetGetInstanceConfig()
    {
        $instanceConfig = array(array('key' => 'val'));
        $this->instanceConfig->setInstanceId($instanceConfig);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(),
            $instanceConfig
        );
    }
} 