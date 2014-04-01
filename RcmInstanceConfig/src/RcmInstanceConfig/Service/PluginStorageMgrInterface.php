<?php
namespace RcmInstanceConfig\Service;

use RcmInstanceConfig\Model\InstanceConfigMerger;
use RcmInstanceConfig\Repo\PluginStorageRepoInterface;

interface PluginStorageMgrInterface
{
    public function getDefaultInstanceConfig($pluginName);

    public function getInstanceConfig($instanceId, $pluginName);

    public function saveInstance($instanceId, $configData);

    public function deleteInstance($instanceId);
}