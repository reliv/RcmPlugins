<?php
/**
 * @category  RCM
 * @author    Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: reliv
 * @link      http://ci.reliv.com/confluence
 */

namespace RcmCallToActionBox\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Rcm\Plugin\BaseController;

class BaseControllerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new BaseController(
            $serviceLocator->get('config'),
            'RcmCallToActionBox'
        );
        return $controller;

    }
}