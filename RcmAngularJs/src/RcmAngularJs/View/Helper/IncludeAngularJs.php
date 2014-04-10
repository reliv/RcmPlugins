<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IncludeAngularJs extends AbstractHelper
{
    public function __invoke()
    {
        return $this;
    }

    protected function injectJs()
    {
        $view = $this->getView();


    }
}