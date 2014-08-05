<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IncludeAngularJs extends AbstractHelper
{
    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        /**
         * this is breaking pages like the /earnmore page by
         * screwing up the load order
         */
        //$this->inject();
    }

//    /**
//     * inject
//     *
//     * @return void
//     */
//    protected function inject()
//    {
//        $view = $this->getView();
//
//        /** @var \Zend\View\Helper\HeadScript $headScript */
//        $headScript = $view->headScript();
//
//        $headScript->prependFile(
//            '/modules/rcm-angular-js/angular/angular.min.js'
//        );
//    }
}