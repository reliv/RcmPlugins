<?php
namespace RcmDoctrineJsonPluginStorage\Service;

use RcmDoctrineJsonPluginStorage\Model\InstanceConfigMerger;
use RcmDoctrineJsonPluginStorage\Repo\PluginStorageRepoInterface;

class PluginStorageMgr 
{
    protected $pluginConfigs;

    /**
     * @var \RcmDoctrineJsonPluginStorage\Model\InstanceConfigMerger
     */
    protected $instanceConfigMerger;

    /**
     * Caches instance configs to speed up multiple calls to getDbInstanceConfig()
     * @var array
     */
    private $instanceConfigs = array();

    /**
     * @var \RcmDoctrineJsonPluginStorage\Repo\PluginStorageRepoInterface
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

    public function getDefaultInstanceConfig($pluginName)
    {
        return $this->pluginConfigs[$pluginName]['defaultInstanceConfig'];
    }

    /**
     * merges the instance config with the new instance config so that default
     * values are used when the db instance config doesn't yet have them after
     * new public functionality is added
     * @param $instanceId
     * @param $pluginName
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
                $instanceConfig = $this->instanceConfigMerger->mergeConfigArrays(
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