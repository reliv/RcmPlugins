<?php

namespace RcmJira\Factory;

use RcmErrorHandler\Model\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ErrorListenerFactory
 *
 * LongDescHere
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

class ErrorListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('\RcmErrorHandler\Config');

        $listenerConfig = $config->get('listener');

        $options = [];

        if(isset($listenerConfig['\RcmJira\ErrorListener'])) {

            $options = $listenerConfig['\RcmJira\ErrorListener'];
        }

        $configOptions = new Config($options);

        $jiraLogger = $serviceLocator->get('\RcmJira\JiraLogger');

        return new \RcmJira\ErrorListener($configOptions, $jiraLogger);
    }
}