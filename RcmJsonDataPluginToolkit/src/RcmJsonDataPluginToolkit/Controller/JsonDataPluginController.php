<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmJsonDataPluginToolkits\RcmJsonDataPluginToolkit
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmJsonDataPluginToolkit\Controller;
use \RcmJsonDataPluginToolkit\Exception\PluginDataNotFoundException;
/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmJsonDataPluginToolkits\RcmJsonDataPluginToolkit
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class JsonDataPluginController
    extends \Zend\Mvc\Controller\AbstractActionController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * @var string Tells function renderInstance() which template to use.
     */
    protected $template;

    /**
     * @var string Tells function renderDefaultInstance() where the default data
     * for a new instance of this plugin is
     */
    protected $defaultJsonContentFilePath;

    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $template = null,
        $defaultJsonContentFilePath = null
    ) {
        $this->entityMgr = $entityMgr;
        $this->template = $template;
        $this->defaultJsonContentFilePath = $defaultJsonContentFilePath;
    }

    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId){
        $view = new \Zend\View\Model\ViewModel(
            array(
                'data' => $this->readJsonDataFromDb($instanceId)->getData()
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderDefaultInstance(){
        $view = new \Zend\View\Model\ViewModel(
            array(
                'data' =>  $this->getDefaultJsonContent()
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Saves a plugin instance to persistent storage
     *
     * @param string $instanceId plugin instance id
     * @param array  $data       posted data to be saved
     *
     * @return null
     */
    function saveInstance($instanceId,$data){
        $this->entityMgr->persist(
            new \RcmJsonDataPluginToolkit\Entity\JsonContent($instanceId, $data)
        );
        $this->entityMgr->flush();
    }

    /**
     * Deletes a plugin instance from persistent storage
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    function deleteInstance($instanceId){
        $entity = $this->readJsonDataFromDb($instanceId);
        $this->entityMgr->remove($entity);
        $this->entityMgr->flush();
    }

    /**
     * Get entity content as JSON. This is called by the editor javascript of
     * some plugins. Urls look like
     * '/rmc-plugin-admin-proxy/rcm-plugin-name/223/admin-data'
     *
     * @param integer $instanceId instance id
     *
     * @return null
     */
    function dataAdminAjaxAction($instanceId)
    {
        if ($instanceId < 0) {
            $content = new \RcmJsonDataPluginToolkit\Entity\JsonContent(
                null, $this->getDefaultJsonContent()
            );
        } else {
            $content = $this->readJsonDataFromDb($instanceId);
        }
        /*
         * @TODO RETURN RESPONSE OBJECT INSTEAD OF EXITING. FOR SOME REASON ZF2
         * DOES NOT RENDER THE RESPONSE OBJECT
         */
        echo $content->getDataAsJson();
        exit();
//        $response = new \Zend\Http\Response();
//        $response->setContent($content->getDataAsJson());
//        $headers=new \Zend\Http\Headers();
//        $headers->addHeaderLine('Content-type','application/json');
//        $response->setHeaders($headers);
//        return $response;
    }

    /**
     * Returns the path of the default json content file. Looks in the default
     * location if this property is not set
     * @return null|string
     */
    public function getDefaultJsonContentFilePath()
    {
        if(!$this->defaultJsonContentFilePath){
            $reflection = new \ReflectionClass(get_class($this));
            return  dirname($reflection->getFileName())
                . '/../../../config/default.content.json';
        }
        return $this->defaultJsonContentFilePath;
    }


    /**
     * Returns the JSON content for a given plugin instance Id
     *
     * @param integer $instanceId plugin instance id
     *
     * @return object
     */
    function readJsonDataFromDb($instanceId)
    {
        $entity = $this->entityMgr
            ->getRepository('RcmJsonDataPluginToolkit\Entity\JsonContent')
            ->findOneByInstanceId($instanceId);
        if (!$entity) {
            throw new PluginDataNotFoundException('Json content not found in DB for instance #'.$instanceId);
        }
        return $entity;
    }

    /**
     * Gets the default JSON content from the file:
     * Content/DefaultJsonContent.php
     *
     * @return object
     */
    function getDefaultJsonContent()
    {
        return $this->readJsonFile(
            $this->getDefaultJsonContentFilePath()
        );
    }

    /**
     * Reads a JSON file and returns a PHP object with the file's data
     *
     * @param string $fileName
     *
     * @return object
     * @throws \RcmJsonDataPluginToolkit\Exception\RuntimeException
     */
    function readJsonFile($fileName){

        $contentObject = json_decode(file_get_contents($fileName));

        if(!$contentObject){
            throw new \RcmJsonDataPluginToolkit\Exception\RuntimeException(
                ' File contains invalid JSON:' .$fileName
            );
        }

        return $contentObject;
    }

    /**
     * Shortcut method to get post
     *
     * @return \Zend\Stdlib\Parameters
     */
    function getPost()
    {
        return $this->getEvent()->getRequest()->getPost();
    }
}
