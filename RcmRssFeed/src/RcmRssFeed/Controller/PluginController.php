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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace RcmRssFeed\Controller;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class PluginController
    extends \RcmInstanceConfig\Controller\BasePluginController
    implements \Rcm\Plugin\PluginInterface
{
    public function renderInstance($instanceId)
    {
        return $this->buildView(
            $instanceId,
            $this->getInstanceConfig($instanceId)
        );
    }

    function buildView($instanceId, $instanceConfig)
    {
        $view = new \Zend\View\Model\ViewModel(array(
            'instanceId' => $instanceId,
            'instanceConfig' => $instanceConfig,
            'rssProxy' => '/rcm-rss-proxy'
        ));
        $view->setTemplate($this->template);
        return $view;
    }
}