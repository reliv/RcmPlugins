<?php

/**
 * Content database Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDoctrineJsonPluginStorages\RcmDoctrineJsonPluginStorage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmDoctrineJsonPluginStorage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content Configbase Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDoctrineJsonPluginStorage\Entity
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_simple_instance_configs")
 */
class InstanceConfig
{
    /**
     * @var integer Plugin instanceId for this content
     *
     * @ORM\Id @ORM\Column(type="integer")
     */
    protected $instanceId;

    /**
     * @var string config that will be stored in the DB as JSON
     *
     * @ORM\Column(type="text")
     */
    protected $config;

    /**
     * Gets the $instanceId property
     *
     * @return string $instanceId
     *
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * Gets the config that will be stored in the DB as JSON
     *
     * @return array
     *
     */

    public function getConfig(){
        return json_decode($this->config, true);
    }

    /**
     * Sets the $instanceId property
     *
     * @param string $instanceId new value
     *
     * @return null
     *
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
    }

    /**
     * Sets the config that will be stored in the DB as JSON
     *
     * @param array $config new value
     *
     * @return null
     *
     */
    public function setConfig($config)
    {
        $this->config = json_encode($config);
    }
}