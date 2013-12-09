<?php
namespace RcmDoctrineJsonPluginStorage\Service;

class PluginStorageMgrMock implements PluginStorageMgrInterface
{
    public $testConfig = array();

    public $lastSavedConfig;
    public $lastSavedInstanceId;
    public $lastDeletedInstanceId;

    public function getDefaultInstanceConfig($pluginName)
    {
        return $this->testConfig;
    }

    public function getInstanceConfig($instanceId, $pluginName)
    {
        return $this->testConfig;
    }

    public function saveInstance($instanceId, $testConfigData)
    {
        $this->lastSavedInstanceId = $instanceId;
        $this->lastSavedConfig = $testConfigData;
    }

    public function deleteInstance($instanceId)
    {
        $this->lastDeletedInstanceId = $instanceId;
    }

    public function setTestConfig($testConfig)
    {
        $this->testConfig = $testConfig;
    }
}