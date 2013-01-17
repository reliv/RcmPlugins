<?php
namespace RcmSimpleConfigStorage\StorageEngine;
use RcmSimpleConfigStorage\Exception\PluginDataNotFoundException,
    \RcmSimpleConfigStorage\Entity\JsonInstanceConfig;
class JsonDoctrineRepo{

    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    protected $jsonContentRepo;

    function __construct(\Doctrine\ORM\EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;
        $this->jsonContentRepo = $this->entityMgr
            ->getRepository('RcmSimpleConfigStorage\Entity\JsonInstanceConfig');
    }

    function getInstanceConfig($instanceId){
        return $this->readEntityFromDb($instanceId)->getConfig();
    }

    function createInstanceConfig($instanceId, $config){
        $entity= new JsonInstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($config);
        $this->entityMgr->persist($entity);
        $this->entityMgr->flush();
    }

    function deleteInstanceConfig($instanceId){
        $entity = $this->readJsonEntityFromDb($instanceId);
        $this->entityMgr->remove($entity);
        $this->entityMgr->flush();
    }

    /**
     * Returns the JSON content for a given plugin instance Id
     * @param $instanceId
     *
     * @return \RcmSimpleConfigStorage\Entity\JsonInstanceConfig|null
     * @throws \RcmSimpleConfigStorage\Exception\PluginDataNotFoundException
     */
    function readEntityFromDb($instanceId)
    {
        $entity = $this->jsonContentRepo->findOneByInstanceId($instanceId);
        if (!$entity) {
            throw new PluginDataNotFoundException(
                'Plugin Config not found in DB instance #'.$instanceId
            );
        }
        return $entity;
    }
}