<?php

namespace RcmAdmin\EventListener;


use Rcm\Service\LayoutManager;
use RcmAdmin\Controller\AdminPanelController;
use Zend\Mvc\MvcEvent;
use Zend\View\HelperPluginManager;
use Zend\View\Model\ViewModel;

class DispatchListener
{

    protected $layoutManager;
    protected $viewHelperManager;
    protected $adminPanelController;

    public function __construct(
        LayoutManager $layoutManager,
        HelperPluginManager $viewHelperManager,
        AdminPanelController $adminPanelController
    ) {
        $this->layoutManager = $layoutManager;
        $this->viewHelperManager = $viewHelperManager;
        $this->adminPanelController = $adminPanelController;
    }

    public function getAdminPanel(MvcEvent $event)
    {
        $adminPanelView = new ViewModel();
        $adminPanelView->setTemplate('admin-panel/panel.phtml');

        $adminNavigation = $this->adminPanelController->getAdminNavigationAction();
        $adminPanelView->addChild($adminNavigation,'rcmAdminNavigation');
        $adminPanelView->terminate(true);

        /** @var \Zend\View\Model\ViewModel $viewModel */
        $layout = $event->getViewModel();
        $layout->addChild($adminPanelView, 'rcmAdminPanel');

        return;
    }

}