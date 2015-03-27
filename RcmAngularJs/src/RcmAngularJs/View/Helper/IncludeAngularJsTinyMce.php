<?php


namespace RcmAngularJs\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeAngularJsTinyMce
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAngularJs\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeAngularJsTinyMce extends AbstractHelper
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

        $headScript->prependFile(
            $view->basePath().'/modules/rcm-angular-js/angular-ui/ui-tinymce/src/tinymce.js'
        );

        $view->rcmIncludeTinyMceJs();
    }
}
