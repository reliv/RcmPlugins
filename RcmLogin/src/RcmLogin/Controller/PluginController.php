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
namespace RcmLogin\Controller;

use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use RcmUser\Service\RcmUserService;
use Zend\Authentication\Result;

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
    extends BaseController
    implements PluginInterface
{

    /**
     * @var \RcmUser\Service\RcmUserService $rcmUserService
     */
    protected $rcmUserService;

    function __construct(
        $config,
        RcmUserService $rcmUserService
    ) {
        parent::__construct($config);
        $this->rcmUserService = $rcmUserService;
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        $postSuccess = false;
        $error = null;
        $username = null;

        if ($this->postIsForThisPlugin()) {
            $username = trim(
                filter_var(
                    $this->getRequest()->getPost('username')
                    ,
                    FILTER_SANITIZE_STRING
                )
            );
            $password = filter_var(
                $this->getRequest()->getPost('password')
                ,
                FILTER_SANITIZE_STRING
            );

            if (empty($username) || empty($password)) {
                $error = $instanceConfig['translate']['missing'];
            }

            $user = $this->rcmUserService->buildNewUser();
            $user->setUsername($username);
            $user->setPassword($password);

            $authResult = $this->rcmUserService->authenticate($user);

            if (!$authResult->isValid()) {

                if ($authResult->getCode() == Result::FAILURE_UNCATEGORIZED
                    && !empty($this->config['rcmPlugin']['RcmLogin']['uncategorizedErrorRedirect'])
                ) {
                    // @todo return $this->redirect()->toUrl($this->config['rcmPlugin']['RcmLogin']['uncategorizedErrorRedirect']);
                    header(
                        'Location: '
                        . $this->config['rcmPlugin']['RcmLogin']['uncategorizedErrorRedirect']
                    );
                    exit();
                }
                $error = $instanceConfig['translate']['invalid'];
            }


            if (!$error) {
                $postSuccess = true;
            }
        }

        if ($postSuccess) {

            $redirectUrl = $this->config['Rcm']['successfulLoginUrl'];

            /**
             * We let the successful login page handel redirects in case it
             * wants to override them
             *
             */
            if (isset($_GET['redirect'])) {
                $redirectUrl .= '?redirect='
                    . filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
            }

            // @todo? $this->redirect()->toUrl($redirectUrl);
            header('Location: ' . $redirectUrl);
            exit();

        } else {

            $view = parent::renderInstance(
                $instanceId,
                $instanceConfig
            );

            $view->setVariables(
                [
                    'error' => $error,
                    'username' => $username,
                ]
            );

        }


        return $view;
    }
}