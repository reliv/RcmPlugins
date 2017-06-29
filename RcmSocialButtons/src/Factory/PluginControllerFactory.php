<?php

namespace RcmSocialButtons\Factory;

use Interop\Container\ContainerInterface;
use RcmSocialButtons\Controller\PluginController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * PluginControllerFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmPriceDisplay\Factory
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
        return new PluginController(
            $container->get('Config')
        );
    }
}
