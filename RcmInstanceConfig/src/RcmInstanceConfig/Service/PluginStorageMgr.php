<?php
namespace RcmInstanceConfig\Service;

use RcmInstanceConfig\Model\InstanceConfigMerger;
use RcmInstanceConfig\Repo\PluginStorageRepoInterface;

class PluginStorageMgr implements PluginStorageMgrInterface
{
    protected $pluginConfigs;

    /**
     * @var \RcmInstanceConfig\Model\InstanceConfigMerger
     */
    protected $instanceConfigMerger;

    /**
     * Caches instance configs to speed up multiple calls to getDbInstanceConfig()
     *
     * @var array
     */
    private $instanceConfigs = array();

    /**
     * @var \RcmInstanceConfig\Repo\PluginStorageRepoInterface
     */
    protected $storageRepo;

    public function __construct(
        PluginStorageRepoInterface $storageRepo,
        $config,
        InstanceConfigMerger $instanceConfigMerger
    )
    {
        $this->pluginConfigs = $config['rcmPlugin'];
        $this->instanceConfigMerger = $instanceConfigMerger;
        $this->storageRepo = $storageRepo;
    }

    /**
     * Default instance configs are NOT required anymore
     *
     * @param string $pluginName the plugins module name
     *
     * @return array
     */
    public function getDefaultInstanceConfig($pluginName)
    {
        $defaultInstanceConfig = array_key_exists(
            'defaultInstanceConfig', $this->pluginConfigs[$pluginName]
        ) ? $this->pluginConfigs[$pluginName]['defaultInstanceConfig'] : array();
        return $defaultInstanceConfig;
    }

    /**
     * merges the instance config with the new instance config so that default
     * values are used when the db instance config doesn't yet have them after
     * new public functionality is added
     *
     * @param $instanceId
     * @param $pluginName
     *
     * @return array
     */
    public function getInstanceConfig($instanceId, $pluginName)
    {
        //Instance configs less than 0 are default instanc configs
        if ($instanceId < 0) {

            return $this->getDefaultInstanceConfig($pluginName);

        } else {

            //Check to see if we already have a cached instance config
            if (!isset($this->instanceConfigs[$instanceId])) {

                //Grab from the db or use blank array if not there
                $instanceConfig = $this->storageRepo->select($instanceId);
                if (!is_array($instanceConfig)) {
                    $instanceConfig = array();
                }

                //Merge the default and db instance configs. Db overwrites.
                $instanceConfig = $this->instanceConfigMerger
                    ->mergeConfigArrays(
                        $this->getDefaultInstanceConfig($pluginName),
                        $instanceConfig
                    );

                //Cache merged instance configs in this object
                $this->instanceConfigs[$instanceId] = $instanceConfig;
            }
            return $this->instanceConfigs[$instanceId];
        }
    }

    public function saveInstance($instanceId, $configData)
    {
        $this->storageRepo->insert($instanceId, $configData);
    }

    public function deleteInstance($instanceId)
    {
        $this->storageRepo->delete($instanceId);
    }
}