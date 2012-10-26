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
namespace RcmLogin\Controller;

use \Zend\View\Model\ViewModel;

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
    extends \RcmJsonDataPluginToolkit\Controller\JsonDataPluginController
    implements \Rcm\Controller\PluginInterface
{
    /**
     * @var string template to render content with
     */
    protected $template = 'rcm-login/plugin';

    public function renderInstance($instanceId)
    {
        $view = parent::renderInstance($instanceId);
        $this->request = $this->getEvent()->getRequest();

        $error = $this->params()->fromQuery('rcmLoginError', null);

        $view->setVariable('rcmLoginError', $error);
            return $view;
    }

    public function getErrorsAction($instanceId)
    {
        $view = new ViewModel(array('data' => json_encode($instanceId)));
        $view->setTemplate('reliv-common/literal');
        return $view;
    }

    function getDefaultJsonContent(){
        $r = parent::getDefaultJsonContent();
        return $r;
    }
}