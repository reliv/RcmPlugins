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
 * @package   PrivatePlugins\RcmLoginLink
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace RcmLoginLink\Controller;

/**
 * Online App Plugin Controller
 *
 * Main controller for the online app
 *
 * @category  Reliv
 * @package   PrivatePlugins\RcmLoginLink
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
    protected $loggedInUser;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        $config,
        \Rcm\Model\UserManagement\UserManagerInterface $userMgr
    ) {
        parent::__construct($entityMgr, $config);
        $this->loggedInUser=$userMgr->getLoggedInUser();
    }

    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function renderInstance($instanceId)
    {
        return $this->injectIsLoggedInIntoView(
            parent::renderInstance($instanceId)
        );
    }

    function renderDefaultInstance($instanceId){
        return $this->injectIsLoggedInIntoView(
            parent::renderDefaultInstance($instanceId)
        );
    }

    function injectIsLoggedInIntoView($view){
        $view->setVariable(
            'isLoggedIn',
            !empty($this->loggedInUser)
        );
        return $view;
    }
}

