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
    implements \Rcm\Controller\PluginInterface
{
    /**
     * @var string Tells function renderInstance() which template to use.
     *
     * This can be overridden in child controllers
     */
    protected $template = 'rcm-html-area/plugin';

    protected $defaultJsonContentFilePath = null;

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
        $this->getEm()->persist(
            new \RcmJsonDataPluginToolkit\Entity\JsonContent($instanceId, $data)
        );
        $this->getEm()->flush();
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
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
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
     * Sets the default content JSON file path
     *
     * @param $defaultJsonContentFilePath
     */
    public function setDefaultJsonContentFilePath($defaultJsonContentFilePath)
    {
        $this->defaultJsonContentFilePath = $defaultJsonContentFilePath;
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
     * Sets the Template property
     *
     * @param string $template
     *
     * @return null
     *
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Gets the Template property
     *
     * @return string Template
     *
     */
    public function getTemplate()
    {
        return $this->template;
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
        $entity = $this->getEm()
            ->getRepository('RcmJsonDataPluginToolkit\Entity\JsonContent')
            ->findOneByInstanceId($instanceId);
        if (!$entity) {
            throw new PluginDataNotFoundException();
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
