<?php

namespace RcmAdmin\Factory;

use RcmAdmin\EventListener\DispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class DispatchListenerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return DispatchListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get('Rcm\\Service\\LayoutManager');

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('viewHelperManager');

        /** @var \RcmAdmin\Controller\AdminPanelController $adminPanel */
        $adminPanel = $serviceLocator->get('RcmAdmin\\Controller\\AdminPanelController');

        return new DispatchListener(
            $layoutManager,
            $viewHelperManager,
            $adminPanel
        );
    }
}
