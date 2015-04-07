<?php

namespace RcmDialog\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeRcmDialog
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmDialog\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeRcmDialog extends AbstractHelper
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

        $headScript->appendFile(
            $view->basePath() . '/modules/rcm-dialog/dialog.js'
        );
    }
}
