<?php

namespace RcmEventListDisplay\Factory;

use RcmEventListDisplay\Controller\PluginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmEventlistDisplayFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmEventListDisplay\Factory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class PluginControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new PluginController(
            $serviceLocator->get('config'),
            $serviceLocator->get('Doctrine\ORM\EntityManager'),
            $serviceLocator->get('CalenderModel')
        );
        return $controller;
    }
}