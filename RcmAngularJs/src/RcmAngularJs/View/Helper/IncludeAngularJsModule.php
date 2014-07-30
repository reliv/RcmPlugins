<?php


namespace RcmAngularJs\View\Helper;

use
    RcmAngularJs\Model\AngularRegistry;
use
    Zend\View\Helper\AbstractHelper;

class IncludeAngularJsModule extends AbstractHelper
{
    /**
     * @var string
     */
    public $modules = array();

    /**
     * __invoke
     *
     * @param $modules
     * @param $scripts
     *
     * @return void
     */
    public function __invoke(
        $modules,
        $scripts
    ) {
        $this->inject(
            $modules,
            $scripts
        );
    }

    /**
     * inject
     *
     * @param $modules
     * @param $scripts
     *
     * @return void
     */
    protected function inject(
        $modules,
        $scripts
    ) {
        $modules = $this->buildArrayValue($modules);
        $scripts = $this->buildArrayValue($scripts);

        AngularRegistry::setModules($modules);

        $this->modules = AngularRegistry::getModules();

        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        foreach ($scripts as $script) {

            $headScript->appendFile(
                $script
            );
        }

        $headScript->prependFile(
            '/modules/rcm-angular-js/angular/angular.min.js'
        );
    }

    /**
     * buildArrayValue
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function buildArrayValue($value)
    {

        if (!is_array($value)) {

            $value = array($value);
        }

        return $value;
    }
}