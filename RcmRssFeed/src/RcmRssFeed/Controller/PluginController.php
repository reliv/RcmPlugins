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
 * @package   RcmPlugins\Navigation
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmRssFeed\Controller;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmPlugins\Navigation
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController
    extends \RcmSimpleConfigStorage\Controller\SimpleConfigStorageController
    implements \Rcm\Plugin\PluginInterface
{
    public function renderInstance($instanceId)
    {
        return $this->buildView(
            $this->configRepo->getInstanceConfig($instanceId)
        );
    }

    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderDefaultInstance()
    {
        return $this->buildView($this->getDefaultJsonContent());
    }

    function buildView($data)
    {
        $view = new \Zend\View\Model\ViewModel(array(
            'data' => $data,
            'rssProxy' => '/rcm-rss-proxy'
        ));
        $view->setTemplate($this->template);
        return $view;
    }



}