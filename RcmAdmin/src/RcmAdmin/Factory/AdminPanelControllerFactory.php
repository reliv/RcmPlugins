<?php

namespace RcmAdmin\Factory;

use RcmAdmin\Controller\AdminPanelController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AdminPanelControllerFactory implements FactoryInterface
{

    /**
     * Factory for the Admin Panel Controller
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return AdminPanelController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        $adminPanelConfig = array();

        if (!empty($config['rcmAdmin']['adminPanel'])
            && is_array($config['rcmAdmin']['adminPanel'])
        ) {
            $adminPanelConfig = $config['rcmAdmin']['adminPanel'];
        }

        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        $siteId = $currentSite->getSiteId();

        return new AdminPanelController(
            $adminPanelConfig,
            $rcmUserService,
            $siteId
        );
    }
}
