<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IncludeAngularJsBootstrap extends AbstractHelper
{
    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        $this->inject();

        return;
    }

    /**
     * inject
     *
     * @return void
     */
    protected function inject()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        $headScript->appendFile(
            $view->basePath().'/modules/rcm-angular-js/angular-ui/bootstrap/ui-bootstrap-tpls-0.11.0.min.js'
        );

        $view->plugin('rcmIncludeTwitterBootstrap')->injectCss();

    }
}