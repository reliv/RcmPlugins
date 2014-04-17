<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 4/16/14
 * Time: 4:13 PM
 */

namespace RcmInstanceConfig\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HtmlPurifierFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', getcwd() . '/data/HTMLPurifier');
        return new \HTMLPurifier($config);
    }
} 