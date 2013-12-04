<?php
namespace RcmDoctrineJsonPluginStorage\Repo;

use Doctrine\ORM\EntityManager;
use RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig;

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

    public function select($instanceId)
    {
        $instanceConfig = $this->entityMgr
            ->getRepository('RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig')
            ->findOneByInstanceId($instanceId);
        if (!$instanceConfig) {
            $instanceConfig = new DoctrineJsonInstanceConfig();
        }
        return $instanceConfig->getConfig();
    }

    public function insert($instanceId, $configData)
    {
        $entity = new DoctrineJsonInstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($configData);
        $this->entityMgr->persist($entity);
        $this->entityMgr->flush($entity);
    }

    public function delete($instanceId)
    {
        $entity = $this->read($instanceId);
        $this->entityMgr->remove($entity);
        $this->entityMgr->flush();
    }
} 