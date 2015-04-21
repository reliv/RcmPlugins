<?php

namespace RcmAxosoft\Factory;

use RcmAxosoft\AxosoftLogger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AxosoftLoggerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAxosoft
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AxosoftLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configRoot = $serviceLocator->get('Config');
        $loggerOptions = $configRoot['RcmAxosoft']['errorLogger'];

        $api = $serviceLocator->get('Reliv\AxosoftApi\Service\AxosoftApi');

        return new AxosoftLogger($api, $loggerOptions);
    }
}