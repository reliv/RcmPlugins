<?php

namespace RcmBrightcovePlayer\Factory;

use Rcm\Plugin\BaseController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RcmBrightcovePlayerControllerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return BaseController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \RcmInstanceConfig\Service\PluginStorageMgrInterface $pluginStorage */
        $pluginStorage = $serviceLocator->get('rcmPluginStorage');

        return new BaseController (
            $pluginStorage,
            $serviceLocator->get('config'),
            'RcmBrightcovePlayer'
        );
    }
}
