<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 12/4/13
 * Time: 2:14 PM
 */

namespace RcmDoctrineJsonPluginStorageTest\Mock;

class PluginStorageMgrMock
{

    protected $instanceConfigs = array();

    public function readInstance($instanceId)
    {
        return $this->instanceConfigs[$instanceId];
    }

    public function saveInstance($instanceId, $configData)
    {
        $this->instanceConfigs[$instanceId] = $configData;
    }

    public function deleteInstance($instanceId)
    {
        unset($this->instanceConfigs[$instanceId]);
    }

    public function getDefaultInstanceConfig()
    {
        return $this->instanceConfigs[0];
    }

    public function getInstanceConfig($instanceId, $pluginName)
    {
        return $this->instanceConfigs[$instanceId];
    }

} 