<?php
/**
 * Include Jquery View helper
 *
 * This view helper includes jquery, jqueryui, and block-ui JS
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmJquery\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Include Jquery View helper
 *
 * This view helper includes jquery, jqueryui, and block-ui JS
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeJquery extends AbstractHelper
{
    /**
     * Adds Jquery JS includes to the html
     *
     * @return null
     */
    public function __invoke()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();
        $headScript->appendFile(
            '/modules/rcm-jquery/jquery-ui-1.10.4.custom/js/jquery-1.10.2.js'
        );
        $headScript->appendFile(
            '/modules/rcm-jquery/jquery-ui-1.10.4.custom/js/'
            . 'jquery-ui-1.10.4.custom.min.js'
        );
        $headScript->appendFile(
            '/modules/rcm-jquery/jquery-block-ui/jquery-block-ui.js'
        );
    }
}