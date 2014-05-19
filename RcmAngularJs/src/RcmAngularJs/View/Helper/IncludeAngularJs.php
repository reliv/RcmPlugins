<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IncludeAngularJs extends AbstractHelper
{
    /**
     *
     */
    const DIR = '/modules/rcm-angular-js/';

    /**
     * @var array
     */
    protected $defaultOptions
        = array(
            'js' => array(
                'angular/angular.js',
            ),
            'css' => array(),
        );

    /**
     * __invoke
     *
     * @param array $options options
     *
     * @return void
     */
    public function __invoke($options = array())
    {
        $this->inject($options);

        return;
    }

    /**
     * inject - angularJs is always included by default.
     *
     * @param array $options array(
     *                          'js' => array(
     *                                  'js/files/to/include'
     *                                  ),
     *                          'css' =>  array(
     *                                  'css/files/to/include'
     *                                  )
     *                          )
     *
     * @return void
     */
    protected function inject($options = array())
    {
        $options = array_merge_recursive($this->defaultOptions, $options);

        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();
        $headLink = $view->headLink();

        foreach ($options['js'] as $file) {

            $file = self::DIR . $file;

            $headScript->appendFile($file);
        }

        foreach ($options['css'] as $file) {

            $file = self::DIR . $file;

            $headLink->appendStylesheet($file);
        }
    }
}