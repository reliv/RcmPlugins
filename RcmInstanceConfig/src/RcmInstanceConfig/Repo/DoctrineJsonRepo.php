<?php
namespace RcmInstanceConfig\Repo;

use Doctrine\ORM\EntityManager;
use RcmInstanceConfig\Entity\DoctrineJsonInstanceConfig;

class DoctrineJsonRepo implements PluginStorageRepoInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    public function __construct(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param $instanceId
     *
     * @return array
     */
    public function select($instanceId)
    {
        /**
         * @var \RcmInstanceConfig\Entity\DoctrineJsonInstanceConfig
         */
        $entity = $this->selectEntity($instanceId);
        if (!is_object($entity)) {
            return array();
        }
        return $entity->getConfig();
    }

    /**
     * @param $instanceId
     * @param $configData
     */
    public function insert($instanceId, $configData)
    {
        $entity = new DoctrineJsonInstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($configData);
        $this->entityMgr->persist($entity);
        $this->entityMgr->flush($entity);
    }

    /**
     * @param $instanceId
     */
    public function delete($instanceId)
    {
        $entity = $this->selectEntity($instanceId);
        if (is_object($entity)) {
            $this->entityMgr->remove($entity);
            $this->entityMgr->flush();
        }
    }

    /**
     * @param $instanceId
     *
     * @return mixed
     */
    public function selectEntity($instanceId)
    {
        $instanceConfig = $this->entityMgr
            ->getRepository(
                'RcmInstanceConfig\Entity\DoctrineJsonInstanceConfig'
            )
            ->findOneByInstanceId($instanceId);
        return $instanceConfig;
    }
} 