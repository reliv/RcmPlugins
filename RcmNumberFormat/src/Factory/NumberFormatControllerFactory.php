<?php

namespace RcmNumberFormat\Factory;

use Interop\Container\ContainerInterface;
use RcmNumberFormat\Controller\NumberFormatController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmNumberFormat
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   NumberFormatController\Factory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class NumberFormatControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return NumberFormatController
     */
    public function __invoke($container)
    {
        return new NumberFormatController(
            $container->get('rcmNumberFormatter'),
            $container->get('rcmCurrencyFormatter')
        );
    }
}
