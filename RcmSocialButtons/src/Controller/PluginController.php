<?php

namespace RcmSocialButtons\Controller;

use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;

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
class PluginController extends BaseController implements PluginInterface
{
    /**
     * @param int   $instanceId
     * @param array $instanceConfig
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId, $instanceConfig)
    {
        $config = $this->getServiceLocator()->get('Config');
        $view = parent::renderInstance($instanceId, $instanceConfig);
        $view->setVariable(
            'availableButtons',
            $config['rcmPlugin']['RcmSocialButtons']['availableButtons']
        );
        return $view;
    }
}
