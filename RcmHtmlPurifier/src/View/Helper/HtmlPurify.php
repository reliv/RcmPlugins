<?php
 /**
 * HtmlPurify.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmHtmlPurifier\Controller\Plugin;

use
    Zend\Mvc\Controller\Plugin\AbstractPlugin;


/**
 * Class HtmlPurify
 *
 * Controller Plugin
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class HtmlPurify extends AbstractPlugin {

    /**
     * __invoke
     * SEE http://htmlpurifier.org/docs for format
     *
     * @param string     $dirtyHtml HTML to be purified
     * @param null|array $allowedElements Array of allowed HTML elements
     *
     * @return string
     */
    public function __invoke($dirtyHtml ,$allowedElements = null)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', getcwd() . '/data/HTMLPurifier');

        if(is_array($allowedElements)){

            $config->set('HTML.AllowedElements', $allowedElements);
        }

        $htmlPurifier = new \HTMLPurifier($config);

        return $htmlPurifier->purify($dirtyHtml);
    }
} 