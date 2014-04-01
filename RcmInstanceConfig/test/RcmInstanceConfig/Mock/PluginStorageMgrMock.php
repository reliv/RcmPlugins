<?php
namespace RcmInstanceConfig\Service;

require_once __DIR__
    . '/../../../src/RcmInstanceConfig/Service/PluginStorageMgrInterface.php';

class PluginStorageMgrMock implements PluginStorageMgrInterface
{
    public $testInstanceConfig = array();

    public $lastSavedConfig;
    public $lastSavedInstanceId;
    public $lastDeletedInstanceId;

    function __construct($testInstanceConfig)
    {
        $this->testInstanceConfig = $testInstanceConfig;
    }

    public function getDefaultInstanceConfig($pluginName)
    {
        return $this->testInstanceConfig;
    }

    public function getInstanceConfig($instanceId, $pluginName)
    {
        return $this->testInstanceConfig;
    }

    public function saveInstance($instanceId, $testInstanceConfigData)
    {
        $this->lastSavedInstanceId = $instanceId;
        $this->lastSavedConfig = $testInstanceConfigData;
    }

    public function deleteInstance($instanceId)
    {
        $this->lastDeletedInstanceId = $instanceId;
    }
}