<?php


namespace RcmDoctrineJsonPluginStorageTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';
require_once __DIR__
    . '/../../../src/RcmDoctrineJsonPluginStorage/Repo/PluginStorageRepoInterface.php';
require_once __DIR__ . '/../Mock/PluginStorageRepoMock.php';

use RcmDoctrineJsonPluginStorage\Model\InstanceConfigMerger;
use RcmDoctrineJsonPluginStorage\Repo\PluginStorageRepoMock;
use RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr;
use RcmTest\Base\BaseTestCase;

class PluginStorageMgrTest extends BaseTestCase
{
    /**
     * @var \RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr
     */
    protected $mgr;

    /**
     * @var \RcmDoctrineJsonPluginStorage\Repo\PluginStorageRepoMock
     */
    protected $repoMock;

    protected $testPluginName = 'testPlugin';

    protected $defaultInstanceConfig = array('numbers' => array(3, 2, 1,));

    public function setUp()
    {
        $this->addModule('RcmDoctrineJsonPluginStorage');
        parent::setUp();

        $this->repoMock = new PluginStorageRepoMock();

        $this->mgr = new PluginStorageMgr(
            $this->repoMock,
            array(
                'rcmPlugin' => array(
                    $this->testPluginName => array(
                        'defaultInstanceConfig' => $this->defaultInstanceConfig
                    )
                )
            ),
            new InstanceConfigMerger()
        );
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr
     */
    public function testGetDefaultInstanceConfig()
    {
        $defaultInstanceConfig = $this->mgr
            ->getInstanceConfig(-1, $this->testPluginName);

        $this->assertEquals(
            $this->defaultInstanceConfig,
            $defaultInstanceConfig
        );
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr
     */
    public function testGetInstanceConfig()
    {
        $extraConfig = array('added' => 1);
        $this->repoMock->testConfigData = $extraConfig;

        $returnedConfig = $this->mgr
            ->getInstanceConfig(1, $this->testPluginName);

        $this->assertEquals(
            array_merge($this->defaultInstanceConfig, $extraConfig),
            $returnedConfig
        );
    }

    /**
     * @covers \RcmDoctrineJsonPluginStorage\Service\PluginStorageMgr
     */
    public function testSaveInstance()
    {
        $id = 1;
        $configData = array('numbers' => array(3, 2, 1,));

        $this->mgr->saveInstance($id, $configData);
        $this->assertEquals(
            $this->repoMock->lastInsertedId,
            $id
        );
        $this->assertEquals(
            $this->repoMock->lastInsertedConfigData,
            $configData
        );
    }

    public function testDeleteInstance()
    {
        $id = 2;

        $this->mgr->deleteInstance(2);
        $this->assertEquals(
            $this->repoMock->lastDeletedId,
            $id
        );
    }
} 