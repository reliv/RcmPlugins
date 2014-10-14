<?php

namespace RcmJira\Factory;

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
class JiraApiFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $configRoot = $serviceLocator->get('Config');
        $configArray = $configRoot['RcmJira']['api'];

        return new \chobie\Jira\Api(
            $configArray['endpoint'],
            new \chobie\Jira\Api\Authentication\Basic(
                $configArray['username'],
                $configArray['password']
            )
        );
    }
}