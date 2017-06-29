<?php

namespace RcmMockPlugin\Factory;

use Interop\Container\ContainerInterface;
use RcmMockPlugin\Controller\PluginController;
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
class PluginControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return PluginController
     */
    public function __invoke($container)
    {
        $controller = new PluginController(
            $container->get('Config'),
            $container->get(\Rcm\Service\Cache::class)
        );

        return $controller;
    }
}
