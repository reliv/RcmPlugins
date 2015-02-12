<?php

namespace RcmErrorHandler\Log\Factory;

use RcmErrorHandler\Log\LoggerErrorListener;
use RcmErrorHandler\Model\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LoggerListenerFactory
 *
 * LoggerListenerFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class LoggerErrorListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $config = $serviceLocator->get('\RcmErrorHandler\Config');

        $listenerConfigs = $config->get('listener');

        $listenerOptions = [];

        if(isset($listenerConfigs['\RcmErrorHandler\Log\LoggerErrorListener']) &&
            isset($listenerConfigs['\RcmErrorHandler\Log\LoggerErrorListener']['options'])) {

            $listenerOptions = $listenerConfigs['\RcmErrorHandler\Log\LoggerErrorListener']['options'];
        }

        $listenerOptions = new Config($listenerOptions);

        return new LoggerErrorListener($listenerOptions, $serviceLocator);
    }
}