<?php
namespace RcmDoctrineJsonPluginStorage\Service;

use RcmDoctrineJsonPluginStorage\Model\InstanceConfigMerger;
use RcmDoctrineJsonPluginStorage\Repo\PluginStorageRepoInterface;

interface PluginStorageMgrInterface
{
    public function getDefaultInstanceConfig($pluginName);

    public function getInstanceConfig($instanceId, $pluginName);

    public function saveInstance($instanceId, $configData);

    public function deleteInstance($instanceId);
}