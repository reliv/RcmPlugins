<?php
/**
 * IncludeTwitterBootstrap.php
 *
 * IncludeTwitterBootstrap
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmTwitterBootstrap\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeTwitterBootstrap
 *
 * IncludeTwitterBootstrap
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAngularJs\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeTwitterBootstrap extends AbstractHelper
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
        $this->injectMeta();
        $this->injectCss();
        $this->injectJs();
    }

    public function injectJs()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        $headScript->appendFile(
            '/modules/rcm-twitter-bootstrap/bootstrap/js/bootstrap.js'
        );

        $headScript->appendFile(
            '/modules/rcm-twitter-bootstrap/html5shiv/html5shiv.js',
            'text/javascript',
            array('conditional' => 'lt IE 9')
        );

        $headScript->appendFile(
            '/modules/rcm-twitter-bootstrap/bootstrap/js/respond/respond.min.js',
            'text/javascript',
            array('conditional' => 'lt IE 9')
        );
    }

    public function injectCss()
    {
        $view = $this->getView();

        $headLink = $view->headLink();

        // NOTE: IE8 does not like minifyied version
        $headLink->prependStylesheet(
            '/modules/rcm-twitter-bootstrap/bootstrap/css/bootstrap.css'
        );

        /** not required *
         * $headLink->prependStylesheet(
         * '/modules/rcm-twitter-bootstrap/bootstrap/css/bootstrap.css.map'
         * );
         * /* */
    }

    public function injectMeta()
    {
        $view = $this->getView();

        $view->headMeta()->appendName(
            'viewport',
            'width=device-width, initial-scale=1'
        );

        $view->headMeta()->appendName(
            'X-UA-Compatible',
            'IE=edge'
        );
    }
}