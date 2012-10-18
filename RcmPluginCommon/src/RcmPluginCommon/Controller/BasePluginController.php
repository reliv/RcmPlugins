<?php
/**
 * Plugin Controller
 *
 * This is the base plugin controller so plugin controllers can share functionality
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPluginCommon\Controller
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmPluginCommon\Controller;

use RcmPluginCommon\Exception\PluginDataNotFoundException,
Zend\Mvc\Controller\AbstractActionController,
Doctrine\ORM\EntityManager;

/**
 * Plugin Controller
 *
 * This is the base plugin controller so plugin controllers can share functionality
 *
 * @category  Reliv
 * @package   RcmPluginCommon\Controller
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class BasePluginController extends \Zend\Mvc\Controller\AbstractActionController
{

    /**
     * @var EntityManager entity manager
     */
    public $entityManager;

    /**
     * Gets the doctrine entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        $emClass='Doctrine\ORM\EntityManager';

        //If the entity manger was not injected, go get it.
        if (!is_a($this->entityManager, $emClass)) {
            $this->entityManager = $this->getServiceLocator()->get($emClass);
        }

        return $this->entityManager;
    }

    /**
     * Sets the doctrine entity manager - this is used for testing only
     *
     * @param $entityManager \Doctrine\ORM\EntityManager doctrine entity manager
     *
     * @return null
     */
    function setEm($entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * Reads one plugin entity from the DB
     *
     * @param integer $instanceId plugin instance id
     * @param string  $className  entity class name
     *
     * @return mixed
     * @throws PluginDataNotFoundException
     */
    function readEntity($instanceId, $className)
    {
        $entity = $this->getEm()->getRepository($className)
            ->findOneByInstanceId($instanceId);
        if (!$entity) {
            throw new PluginDataNotFoundException(
                "Instance #$instanceId of $className not in DB."
            );
        }
        return $entity;
    }

    /**
     * Reads multiple plugin entities from the DB
     *
     * @param integer $instanceId plugin instance id
     * @param string  $className  entity class name
     *
     * @return mixed
     * @throws PluginDataNotFoundException
     */
    function readEntities($instanceId, $className)
    {
        $entities = $this->getEm()->getRepository($className)
            ->findByInstanceId($instanceId);
        if (!$entities) {
            throw new PluginDataNotFoundException(
                "Instance #$instanceId of $className not in DB."
            );
        }
        return $entities;
    }

    /**
     * Deletes one plugin entity from the DB
     *
     * @param integer $instanceId plugin instance id
     * @param string  $className  entity class name
     *
     * @return null
     */
    function deleteEntity($instanceId, $className)
    {
        $entity = $this->readEntity($instanceId, $className);
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
    }

    /**
     * Deletes multiple plugin entities from the DB
     *
     * @param integer $instanceId plugin instance id
     * @param string  $className  entity class name
     *
     * @return null
     */
    function deleteEntities($instanceId, $className)
    {
        $entities = $this->readEntities($instanceId, $className);
        foreach ($entities as $entity) {
            $this->getEm()->remove($entity);
        }
        $this->getEm()->flush();
    }
}
