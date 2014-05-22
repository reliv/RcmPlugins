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
        $this->injectJs();
        $this->injectCss();
    }

    public function injectJs()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();
        $headLink = $view->headLink();

        $headScript->appendFile('/modules/rcm-twitter-bootstrap/js/bootstrap.js');

        $view->rcmIncludeJquery();
    }

    public function injectCss()
    {
        $view = $this->getView();

        $headLink = $view->headLink();

        $headLink->prependStylesheet(
            '/modules/rcm-twitter-bootstrap/css/bootstrap.min.css'
        );

        $headLink->prependStylesheet(
            '/modules/rcm-twitter-bootstrap/css/bootstrap.css.map'
        );
    }
}