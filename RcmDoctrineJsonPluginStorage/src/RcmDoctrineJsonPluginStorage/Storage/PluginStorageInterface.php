<?php
namespace RcmDoctrineJsonPluginStorage\Storage;


use Doctrine\ORM\EntityManager;
use RcmDoctrineJsonPluginStorage\Entity\InstanceConfig;

interface PluginStorageInterface
{
    public function readInstance($instanceId);

    public function saveInstance($instanceId, $configData);

    public function deleteInstance($instanceId);
}