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
        $adminWrapper = $this->adminPanelController->getAdminWrapperAction();

        if (!$adminWrapper instanceof ViewModel) {
            return;
        }

        /** @var \Zend\View\Model\ViewModel $viewModel */
        $layout = $event->getViewModel();
        $layout->addChild($adminWrapper, 'rcmAdminPanel');

        return;
    }

}