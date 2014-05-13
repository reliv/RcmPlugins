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
     *
     */
    const DIR = '/modules/rcm-twitter-bootstrap/';

    /**
     * @var array
     */
    protected $defaultOptions
        = array(
            'css' => array(
                'css/bootstrap.min.css',
                //'css/bootstrap.css.map',
            ),
            'js' => array(),
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
     * inject
     *
     * @param array $options options
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