<?php
/**
 * Service Factory for the Admin Page Controller
 *
 * This file contains the factory needed to generate a Admin Page Controller.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmAdmin\Factory;

use RcmAdmin\Controller\PageController;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Admin Page Controller
 *
 * Factory for the Admin Page Controller.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PageControllerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controler Manager
     *
     * @return PageController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr  For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerMgr->getServiceLocator();

        /** @var \Rcm\Service\PageManager $pageManager */
        $pageManager = $serviceLocator->get('Rcm\Service\PageManager');

        return new PageController($pageManager);
    }
}
