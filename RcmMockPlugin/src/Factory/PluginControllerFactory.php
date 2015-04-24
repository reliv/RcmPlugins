<?php

namespace RcmMockPlugin\Factory;

use RcmMockPlugin\Controller\PluginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * PluginControllerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMockPlugin\Factory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class PluginControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new PluginController(
            $serviceLocator->get('Rcm\Service\Cache')
        );
        return $controller;
    }
}