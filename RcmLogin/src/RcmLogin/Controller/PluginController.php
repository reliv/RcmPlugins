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


use Doctrine\ORM\EntityManager;
use Rcm\Model\UserManagement\UserManagerInterface;
use Rcm\Plugin\PluginInterface;
use RcmSimpleConfigStorage\Controller\SimpleConfigStorageController;
use Rcm\Entity\Site;

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
    extends SimpleConfigStorageController
    implements PluginInterface
{

    protected $userMgr;

    /**
     * @var Site
     */
    protected $site;

    function __construct(
        EntityManager $entityMgr,
        $config,
        UserManagerInterface $userMgr,
        Site $site
    )
    {
        parent::__construct($entityMgr, $config);
        $this->userMgr = $userMgr;
        $this->site = $site;
    }

    public function renderInstance($instanceId)
    {
        $instanceConfig = $this->getInstanceConfig($instanceId);
        $this->ensureHttps();
        $this->request = $this->getEvent()->getRequest();

        $postSuccess = false;
        $error = null;
        $username = null;

        if ($this->postIsForThisPlugin('RcmLogin')) {
            $username = trim(filter_var(
                $this->getRequest()->getPost('username')
                , FILTER_SANITIZE_STRING
            ));
            $password = filter_var(
                $this->getRequest()->getPost('password')
                , FILTER_SANITIZE_STRING
            );

            if (empty($username) || empty($password)) {
                $error = $instanceConfig['translate']['missing'];
            }

            try {
                $user = $this->userMgr->loginUser($username, $password);
            } catch (\Exception $e) {
                $error = $instanceConfig['translate']['systemFailure'];
            }

            if (empty($user)) {
                $error = $instanceConfig['translate']['invalid'];
            }

            if (!$error) {
                $postSuccess = true;
            }
        }

        if ($postSuccess) {

            $redirectUrl = $this->config['Rcm']['successfulLoginUrl'];

            if (isset($_GET['redirect'])) {
                //Prevents attackers from creating off-site redirects
                $redirectUrl = str_replace(
                    array('http://', 'https://', '//'),
                    null,
                    filter_var($_GET['redirect'], FILTER_SANITIZE_URL)
                );
            }

            header('Location: ' . $redirectUrl);
            exit();

        } else {

            $view = parent::renderInstance($instanceId,
                array(
                    'error' => $error,
                    'username' => $username,
                )
            );

        }


        return $view;
    }

    function renderDefaultInstance($instanceId)
    {
        return parent::renderInstance(
            $instanceId,
            array(
                'error' => null,
                'username' => null,
            )
        );
    }

    function ensureHttps()
    {
        if (!$this->isHttps()) {
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