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
    \RcmSimpleConfigStorage\Controller\SimpleConfigStorageController;

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
class PluginController extends SimpleConfigStorageController
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
        $instanceConfig=$this->getInstanceConfig($instanceId);

        $image = $this->getRandomImage($instanceConfig);

        $view = new ViewModel(
            array(
                'image' => $image,
                'ic' => $instanceConfig
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    public function getRandomImage($instanceConfig)
    {
        $images = $instanceConfig['images'];
        $image = $images[array_rand($images, 1)];

        return $image;
    }
}
