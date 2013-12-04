<?php
namespace RcmDoctrineJsonPluginStorage\Storage;


use Doctrine\ORM\EntityManager;
use RcmDoctrineJsonPluginStorage\Entity\InstanceConfig;

class DoctrineJsonPluginStorage implements PluginStorageInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    public function __construct(
        EntityManager $entityMgr
    )
    {
        $this->entityMgr = $entityMgr;
    }

    public function readInstance($instanceId){
        $instanceConfig = $this->entityMgr
            ->getRepository('RcmDoctrineJsonPluginStorage\Entity\InstanceConfig')
            ->findOneByInstanceId($instanceId);
        if (!$instanceConfig) {
            $instanceConfig = new InstanceConfig();
        }
        return $instanceConfig;
    }

    public function saveInstance($instanceId, $configData)
    {
        $entity = new InstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($configData);
        $this->entityMgr->persist($entity);
        $this->entityMgr->flush($entity);
    }

    public function deleteInstance($instanceId)
    {
        $entity = $this->readEntityFromDb($instanceId);
        $this->entityMgr->remove($entity);
        $this->entityMgr->flush();
    }
}