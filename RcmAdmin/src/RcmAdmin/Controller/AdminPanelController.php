<?php

namespace RcmAdmin\Controller;

use Rcm\Service\SiteManager;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\ViewModel;

class AdminPanelController
{
    protected $config;

    /** @var \RcmUser\Service\RcmUserService  */
    protected $userService;

    protected $siteManager;

    /**
     * Constructor
     *
     * @param array          $config      Admin Config
     * @param RcmUserService $userService RmcUser User Service
     * @param SiteManager    $siteManager Rcm Site Manager
     */
    public function __construct($config,
        RcmUserService $userService,
        SiteManager    $siteManager
    ) {
        $this->config = $config;
        $this->userService = $userService;
        $this->siteManager = $siteManager;
    }

    /**
     * Get the Admin Menu Bar
     *
     * @return mixed
     */
    public function getAdminNavigationAction()
    {

        $allowed = $this->userService->isAllowed(
            'Sites.'.$this->siteManager->getCurrentSiteId(),
            'admin',
            '\Rcm\Acl\ResourceProvider'
        );

        if (!$allowed) {
            return null;
        }

        $view = new ViewModel();
        $view->setVariable('adminMenu', $this->config['rcmAdmin']['adminPanel']);
        $view->setTemplate('rcm-admin/panel/navigation');
        return $view;
    }
}