<?php

/**
 * Content Database Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPluginCommons\RcmPluginCommon
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmPluginCommon\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content Database Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPluginCommon\Entity
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_common_jsoncontent")
 */
class JsonContent
{
    /**
     * @var integer Plugin instanceId for this content
     *
     * @ORM\Id @ORM\Column(type="integer")
     */
    protected $instanceId;

    /**
     * @var string data that will be stored in the DB as JSON
     *
     * @ORM\Column(type="text")
     */
    protected $data;

    /**
     * Sets properties
     *
     * @param integer $instanceId instance id
     * @param array|object $data data that will be stored in the DB as JSON
     *
     * @return null
     */
    function __construct($instanceId=null, $data)
    {
        $this->setInstanceId($instanceId);
        $this->setData($data);
    }

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
     * Gets the data that will be stored in the DB as JSON
     *
     * @return object
     *
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    public function getDataAsArray(){
        return json_decode($this->data,true);
    }

    /**
     * Gets the data that will be stored in the DB as JSON without decoding it
     * 
     * @return string
     */
    public function getDataAsJson(){
        return $this->data;
    }

    /**
     * Sets the data that will be stored in the DB from a JSON string
     *
     * @param string $json to store
     */
    function setDataFromJson($json){
        $this->data=$json;
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
     * Sets the data that will be stored in the DB as JSON
     *
     * @param string $data new value
     *
     * @return null
     *
     */
    public function setData($data)
    {
        $this->data = json_encode($data);
    }
}