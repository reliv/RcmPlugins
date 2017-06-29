<?php

namespace RcmRssFeed\Factory;

use Interop\Container\ContainerInterface;
use RcmRssFeed\Controller\PluginController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Layout Manager
 *
 * Factory for the Layout Manager.
 *
 * @category  Reliv
 * @package   RcmPlugins
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PluginControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return PluginController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        $config = $container->get('Config');

        return new PluginController($config);
    }
}
