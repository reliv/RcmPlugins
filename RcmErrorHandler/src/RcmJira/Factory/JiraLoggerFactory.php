<?php

namespace RcmJira\Factory;

use RcmJira\JiraLogger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class JiraLoggerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJira
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class JiraLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $config = $serviceLocator->get('\RcmErrorHandler\Config');

        $api = $serviceLocator->get('\RcmJira\Api');

        $listenerConfigs = $config->get('listener');

        $listenerConfig = [];

        if(isset($listenerConfigs['\RcmJira\ErrorListener'])) {

            $listenerConfig = $listenerConfigs['\RcmJira\ErrorListener'];
        }

        $loggerOptions = [];

        if(isset($listenerConfig['options'])){

            $loggerOptions = $listenerConfig['options'];
        }

        return new JiraLogger($api, $loggerOptions);
    }
}