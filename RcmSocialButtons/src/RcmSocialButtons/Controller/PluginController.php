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
 * @package   RcmPlugins\RcmSocialButtons
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmSocialButtons\Controller;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmSocialButtons
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginController
    extends \RcmPluginCommon\Controller\JsonDataPluginController
    implements \Rcm\Controller\PluginControllerInterface
{
    /**
     * @var string tells pluginAction() which template to use
     */
    protected $template='rcm-social-buttons/plugin';

    function AdminAvailableButtonsAction(){
        $config = $this->getServiceLocator()->get('config');
        $availableButtons
            = json_encode(
                $config['rcmPlugin']['RcmSocialButtons']['availableButtons']
            );
        header('Content-type: application/json');
        exit($availableButtons);
    }
}