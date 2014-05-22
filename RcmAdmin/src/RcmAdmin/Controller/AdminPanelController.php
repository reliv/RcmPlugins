<?php

namespace RcmAdmin\Controller;

use Zend\View\Model\ViewModel;

class AdminPanelController
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getAdminNavigationAction()
    {
        $view = new ViewModel();
        $view->setVariable('adminMenu', $this->config['rcmAdmin']['adminPanel']);
        $view->setTemplate('rcm-admin/panel/navigation');
        return $view;
    }
}