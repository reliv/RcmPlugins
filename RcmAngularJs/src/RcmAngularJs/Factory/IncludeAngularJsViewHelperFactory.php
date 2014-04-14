<?php

namespace RcmAngularJs\Factory;

use Rcm\View\Helper\Container;
use RcmAngularJs\View\Helper\IncludeAngularJs;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IncludeAngularJsViewHelperFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $viewServiceManager
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {

        return new IncludeAngularJs();
    }
}
