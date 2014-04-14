<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IncludeAngularJs extends AbstractHelper
{
    public function __invoke()
    {
        $this->injectJs();
        return;
    }

    protected function injectJs()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();
        $headScript->appendFile('modules/rcm-angular-js/js/angularjs/angular.min.js');
    }
}