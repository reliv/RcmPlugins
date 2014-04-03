<?php
namespace RcmInstanceConfig\Repo;

/**
 * @codeCoverageIgnore
 */
class PluginStorageRepoMock implements PluginStorageRepoInterface
{
    public $testConfigData = array();

    public $lastInsertedId;
    public $lastInsertedConfigData;
    public $lastDeletedId;

    public function select($instanceId)
    {
        return $this->testConfigData;
    }

    public function insert($instanceId, $configData)
    {
        $this->lastInsertedId = $instanceId;
        $this->lastInsertedConfigData = $configData;
    }

    public function delete($instanceId)
    {
        $this->lastDeletedId = $instanceId;
    }
}