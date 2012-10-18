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
 * @package   RcmPluginCommons\RcmPluginCommon
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmPluginCommon\Controller;

use RcmPluginCommon\Controller\BasePluginController;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmPluginCommons\RcmPluginCommon
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class JsonDataPluginControllerAbstract extends BasePluginController
    implements \Rcm\Controller\PluginControllerInterface
{
    /**
     * @var string Tells function pluginAction() which template to use.
     *
     * This can be overridden in child controllers
     */
    protected $template = 'rcm-html-area/plugin';

    /**
     * @var String The default content class name.
     *
     * This can be overridden in child controllers
     *
     * Used by functions pluginAction(), saveAction(), and deleteAction();
     */
    protected $storageClass = 'RcmPluginCommon\Entity\JsonContent';

    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function pluginAction($instanceId)
    {
        if ($instanceId < 0) {
            $content = new \RcmPluginCommon\Entity\JsonContent(
                null, $this->getDefaultJsonContent()
            );
        } else {
            $content = $this->getJsonContent($instanceId);
        }
        $view
            = new \Zend\View\Model\ViewModel(array(
            'data' => $content->getData()
        ));
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Save Action - saves input data for a plugin instance to DB
     *
     * @param string $instanceId plugin instance id
     * @param array  $data       posted data to be saved
     *
     * @return null
     */
    function saveAction($instanceId, $data)
    {
        $this->getEm()->persist(
            new $this->storageClass($instanceId, $data)
        );
        $this->getEm()->flush();
    }

    /**
     * Delete Action - Deletes all data for a plugin instance from DB
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    function deleteAction($instanceId)
    {
        $this->deleteEntity($instanceId, $this->storageClass);
    }

    /**
     * Get entity content as JSON. This is called by the editor javascript of
     * some plugins. Urls look like
     * '/rmc-plugin-admin-proxy/rcm-plugin-name/223/admin-content'
     *
     * @param integer $instanceId instance id
     *
     * @return null
     */
    function AdminContentAction($instanceId)
    {
        if ($instanceId < 0) {
            $content = new \RcmPluginCommon\Entity\JsonContent(
                null, $this->getDefaultJsonContent()
            );
        } else {
            $content = $this->readEntity($instanceId, $this->storageClass);
        }
        header('Content-type: application/json');
        exit($content->getDataAsJson());
    }

    /**
     * Returns the JSON content for a given plugin instance Id
     *
     * @param integer $instanceId plugin instance id
     *
     * @return object
     */
    function getJsonContent($instanceId)
    {
        return $this->readEntity($instanceId, $this->storageClass);
    }

    /**
     * Gets the default JSON content from the file:
     * Content/DefaultJsonContent.php
     *
     * @return object
     */
    function getDefaultJsonContent()
    {
        $reflection = new \ReflectionClass(get_class($this));

        return $this->readJsonFile(
            dirname($reflection->getFileName())
                . '/../../../config/default.content.json'
        );
    }

    /**
     * Reads a JSON file and returns a PHP object with the file's data
     *
     * @param string $fileName
     *
     * @return object
     * @throws \RcmPluginCommon\Exception\RuntimeException
     */
    function readJsonFile($fileName){

        $contentObject = json_decode(file_get_contents($fileName));

        if(!$contentObject){
            throw new \RcmPluginCommon\Exception\RuntimeException(
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
