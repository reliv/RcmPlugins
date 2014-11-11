<?php

namespace EventAPIControllerFactory\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RcmEventCalenderCore\Controller\EventAPIController;
/**
 * EventAPIControllerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   EventAPIControllerFactory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

class EventAPIControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EventAPIController(
            $serviceLocator->get('CalenderModel'),
            $serviceLocator->get('RcmUser\Service\RcmUserService')
        );
    }
}