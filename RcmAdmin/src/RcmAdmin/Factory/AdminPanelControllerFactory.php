<?php

namespace RcmAdmin\Factory;

use RcmAdmin\Controller\AdminPanelController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AdminPanelControllerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return AdminPanelController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AdminPanelController(
            $serviceLocator->get('config')
        );
    }
}
