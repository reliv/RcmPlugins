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

namespace RcmEventCalenderCore;

use RcmEventCalenderCore\Controller\CategoryAPIController;
use RcmEventCalenderCore\Controller\EventAPIController;
use RcmEventCalenderCore\Model\Calender;

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
     * getServiceConfiguration is used by the ZF2 service manager in order
     * to create new objects.
     *
     * @return object Returns an object.
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CalenderModel' => function ($serviceMgr) {
                        $service = new Calender(
                            $serviceMgr->get('Doctrine\ORM\EntityManager')
                        );
                        return $service;
                    }
            )
        );
    }

    function getControllerConfig()
    {
        return array(
            'factories' => array(
                'EventAPIController' => function ($controllerMgr) {
                        $serviceMgr = $controllerMgr->getServiceLocator();
                        return new EventAPIController(
                            $serviceMgr->get('CalenderModel'),
                            $serviceMgr->get('RcmUser\Service\RcmUserService')
                        );
                    },
                'CategoryAPIController' => function ($controllerMgr) {
                        $serviceMgr = $controllerMgr->getServiceLocator();
                        return new CategoryAPIController(
                            $serviceMgr->get('CalenderModel'),
                            $serviceMgr->get('RcmUser\Service\RcmUserService')
                        );
                    }
            )
        );
    }
}