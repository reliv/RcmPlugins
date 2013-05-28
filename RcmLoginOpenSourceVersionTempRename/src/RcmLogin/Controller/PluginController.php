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
namespace RcmLoginOpenSourceVersionTempRename\Controller;

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
    extends \RcmSimpleConfigStorage\Controller\SimpleConfigStorageController
    implements \Rcm\Plugin\PluginInterface
{
    /**
     * @var string template to render content with
     */
    protected $template = 'rcm-login/plugin';

    protected $sessionMgr;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $config,
        \Zend\Session\SessionManager $sessionMgr
    ) {
        parent::__construct($entityMgr, $config);
        $this->sessionMgr = $sessionMgr;
    }

    public function renderInstance($instanceId)
    {
        $this->ensureHttps();
        $view = parent::renderInstance($instanceId);
        $this->request = $this->getEvent()->getRequest();

        $error = $this->params()->fromQuery('rcmLoginError', null);

        $sessionId = $this->sessionMgr->getId();

        $view->setVariable('rcmLoginError', $error);
        $view->setVariable('sessionId', $sessionId);

        return $view;
    }

//    public function getErrorsAction($instanceId)
//    {
//        $view = new ViewModel(array('data' => json_encode($instanceId)));
//        $view->setTemplate('reliv-common/literal');
//        return $view;
//    }

    function ensureHttps(){
        if(!$this->isHttps()){
            $redirectUrl =
                'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            header('Location: ' . $redirectUrl);
            exit();
        }
    }

    function isHttps()
    {
        return (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : null) == 'on';
    }
}