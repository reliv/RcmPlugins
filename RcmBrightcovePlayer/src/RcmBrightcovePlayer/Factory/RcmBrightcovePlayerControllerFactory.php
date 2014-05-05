<?php

namespace RcmBrightcovePlayer\Factory;

use RcmInstanceConfig\Controller\BasePluginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RcmBrightcovePlayerControllerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return BasePluginController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \RcmInstanceConfig\Service\PluginStorageMgrInterface $pluginStorage */
        $pluginStorage = $serviceLocator->get('rcmPluginStorage');

        return new BasePluginController (
            $pluginStorage,
            $serviceLocator->get('config'),
            'RcmBrightcovePlayer'
        );
    }
}
