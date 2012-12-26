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
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmRotatingImage\Controller;

use \Zend\View\Model\ViewModel,
    \RcmJsonDataPluginToolkit\Controller\JsonDataPluginController;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController extends JsonDataPluginController
{

    /**
     * Plugin Action - Returns customer-facing view
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId)
    {
        $data=$this->readJsonDataFromDb($instanceId);
        $images=$data->images;
        $image = $images[array_rand($images, 1)];
        $view = new ViewModel(
            array(
                'image' => $image,
                'data' => $data
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }
}
