<?php

/**
 * Online App Plugin Controller
 *
 * Main controller for the online app
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   PrivatePlugins\RcmEventCalender
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmEventCalender\Controller;

/**
 * Online App Plugin Controller
 *
 * Main controller for the online app
 *
 * @category  Reliv
 * @package   PrivatePlugins\RcmEventCalender
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController
    extends \RcmJsonDataPluginToolkit\Controller\JsonDataPluginController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId)
    {
        $data=$this->readJsonDataFromDb($instanceId);
        $categoryName=$data->category;

        $repo=$this->entityMgr->getRepository('RcmEventCalender\Category');
        $category=$repo->findByName($categoryName);
        $events = $category->getEvents();

        $view = parent::renderInstance($instanceId);
        $view->setVariable('events',$events);
        return $view;
    }

    function renderDefaultInstance(){
        $view = parent::renderDefaultInstance();
        $view->setVariable('events',array());
        return $view;
    }
}

