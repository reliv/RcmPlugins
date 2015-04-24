<?php

namespace RcmNumberFormat\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RcmNumberFormat\Controller\NumberFormatController;

/**
 * RcmNumberFormat
 *
 * LongDescHere
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
class NumberFormatControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new NumberFormatController(
            $serviceLocator->get('rcmNumberFormatter'),
            $serviceLocator->get('rcmCurrencyFormatter')
        );
        return $controller;
    }
}