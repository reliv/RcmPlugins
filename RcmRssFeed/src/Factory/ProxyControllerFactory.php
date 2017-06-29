<?php

namespace RcmRssFeed\Factory;

use Interop\Container\ContainerInterface;
use RcmRssFeed\Controller\ProxyController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Proxy Controller
 *
 * Factory for the Rss Proxy Controller.
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
class ProxyControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return ProxyController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new ProxyController(
            $container->get('Config'),
            $container->get(\Rcm\Service\CurrentSite::class)->getSiteId(),
            $container->get('RcmRssFeed\Cache')
        );
    }
}
