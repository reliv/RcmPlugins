<?php
    /**
     * Index Controller for the entire application
     *
     * This file contains the main controller used for the application.  This
     * should extend from the base class and should need no further modification.
     *
     * PHP version 5.3
     *
     * LICENSE: No License yet
     *
     * @category  Reliv
     * @package   Main\Application\Controllers\Index
     * @author    Unkown <unknown@relivinc.com>
     * @copyright 2012 Reliv International
     * @license   License.txt New BSD License
     * @version   GIT: <git_id>
     * @link      http://ci.reliv.com/confluence
     */
namespace RcmLogin\Controller;

use \Rcm\Controller\BaseController;


/**
 * Login Controller for the login Plugin
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @package   Main\Application\Controllers\Index
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class LoginController extends BaseController
{
    protected $userMgr;

    public function __construct(
        \Rcm\Model\UserManagement\UserManagerInterface $userMgr,
        \Rcm\Model\PluginManager $pluginManager,
        \Doctrine\ORM\EntityManager $entityMgr,
        \Zend\View\Renderer\PhpRenderer $viewRenderer,
        $config
    ) {
        parent::__construct($userMgr, $pluginManager, $entityMgr, $viewRenderer, $config);
        $this->userMgr = $userMgr;
    }

    public function loginAuthAction()
    {
        /** @var \Zend\Stdlib\Parameters $posted  */
        $username = $this->getRequest()->getPost()->get('username');
        $password = $this->getRequest()->getPost()->get('password');

        if (empty($username) || empty($password)) {
            $this->sendInvalid('missing');
        }

        /** @var \Rcm\Model\UserManagement\DoctrineUserManager $userManager  */
        $userManager = $this->userMgr;

        try {
            $user = $userManager->loginUser($username, $password);
        } catch (\Exception $e) {
            $this->sendInvalid('systemFailure');
        }

        if (empty($user)) {
            $this->sendInvalid('invalid');
        }

        $return = array(
            'dataOk' => true,
            'redirectUrl' =>$this->config['rcmPlugin']['RcmLogin']
                ['postLoginRedirectUrl']
        );

        echo json_encode($return);
        exit;
    }

    /**
     * @param string $invalidType Possible - invalid, missing, systemFailure
     */
    private function sendInvalid($invalidType) {
        $return = array(
            'dataOk' => false,
            'error' => $invalidType
        );

        echo json_encode($return);
        exit;
    }
}