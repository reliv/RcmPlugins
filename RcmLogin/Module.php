<?php

/**
 * Module Config For ZF2
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

namespace RcmLogin;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Console\Request as ConsoleRequest;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 */
class Module
{
    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * getServiceConfiguration is used by the ZF2 service manager in order
     * to create new objects.
     *
     * @return object Returns an object.
     *
     * public function getServiceConfig()
     * {
     * return array(
     *
     * );
     * }
     */

    /**
     * New Init process for ZF2.
     *
     * @param ModuleManager $moduleManager ZF2 Module Manager.  Passed in
     *                                     from the service manager.
     *
     * @return null
     */

    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(
            'RcmLogin',
            'dispatch',
            array($this, 'baseControllerInit'),
            12
        );

    }

    /**
     * Event Listener for the Base Controller.
     *
     * @param Event $event ZF2 Called Event
     *
     * @return null
     */
    public function baseControllerInit($event)
    {
        $object = $event->getTarget();

        if (is_subclass_of(
            $object,
            'Rcm\Controller\BaseController'
        )
        ) {
            $object->init();
        }
    }

    /**
     * onBootstrap
     *
     * @param MvcEvent $event event
     *
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {

        $serviceManager = $event->getApplication()->getServiceManager();

        $request = $serviceManager->get('request');

        if ($request instanceof ConsoleRequest) {
            return;
        }

        $application = $event->getApplication();
        $eventManager = $application->getEventManager();
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            array($this, 'doLogout'),
            100
        );
    }

    /**
     * doLogout
     *
     * @param EventInterface $event event
     *
     * @return void
     */
    public function doLogout(EventInterface $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();

        /** @var $request \Zend\Http\Request */
        $request = $sm->get('request');
        $logout = (bool)$request->getQuery('logout', 0);

        if ($logout) {

            /** @var $rcmUserService \RcmUser\Service\RcmUserService */
            $rcmUserService = $sm->get('RcmUser\Service\RcmUserService');
            $rcmUserService->clearIdentity();
        }
    }
}