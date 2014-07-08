<?php
namespace RcmInstanceConfig\Service;

interface PluginStorageMgrInterface
{
    public function getDefaultInstanceConfig($pluginName);

    public function getInstanceConfig($instanceId, $pluginName);

    public function saveInstance($instanceId, $configData);

    public function deleteInstance($instanceId);
}