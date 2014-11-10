<?php

namespace CategoryAPIControllerFactory\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RcmEventCalenderCore\Controller\CategoryAPIController;
/**
 * CategoryAPIControllerFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   CategoryAPIControllerFactory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

class CategoryAPIControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CategoryAPIController(
            $serviceLocator->get('CalenderModel'),
            $serviceLocator->get('RcmUser\Service\RcmUserService')
        );
    }
}