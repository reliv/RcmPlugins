<?php


namespace RcmTinyMce\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeTinyMce
 *
 * IncludeTinyMce View helper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTinyMce\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeTinyMce extends AbstractHelper
{
    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke($options = [])
    {
        $this->inject($options);

        return;
    }

    /**
     * inject
     *
     * @param array $options
     *
     * @return void
     */
    protected function inject($options = [])
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        $headScript->appendFile(
            '/modules/rcm-tinymce-js/tinymce/tinymce.js'
        );

    }
}
