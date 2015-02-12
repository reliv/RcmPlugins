<?php
/**
 * HeadScriptWithErrorHandlerFirst.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\ViewHelper;

use Zend\View\Helper\HeadScript;


/**
 * HeadScriptWithErrorHandlerFirst
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class HeadScriptWithErrorHandlerFirst extends HeadScript
{
    /**
     * Ensure JS error handler is the first JS on page
     *
     * @param  string|int $indent Amount of whitespaces or string to use for indention
     *
     * @return string
     */
    public function toString($indent = null)
    {
        return
            "\n<script type=\"text/javascript\" src=\"/modules/rcm-error-handler/js-error-logger.js\"></script>\n"
            . parent::toString($indent);

    }
} 