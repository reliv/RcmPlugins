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
 *
 */
class PluginController
    extends \RcmDoctrineJsonPluginStorage\Controller\BasePluginController
    implements \Rcm\Plugin\PluginInterface
{
    function availableButtonsAdminAjaxAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $availableButtons
            = json_encode(
            $config['rcmPlugin']['RcmSocialButtons']['availableButtons']
        );
        header('Content-type: application/json');
        exit($availableButtons);
    }
}