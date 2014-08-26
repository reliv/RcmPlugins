<?php
/**
 * IncludeJqueryTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmJqueryTest;

require_once __DIR__ . '/../../../../../../Rcm/test/Base/BaseTestCase.php';
use RcmJquery\View\Helper\IncludeJquery;
use RcmTest\Base\BaseTestCase;
use Zend\View\Renderer\PhpRenderer;

/**
 * IncludeJqueryTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeJqueryTest extends BaseTestCase
{
    function setup()
    {
        $this->addModule('RcmJquery');
        parent::setup();
    }

    /**
     * @covers RcmJquery\View\Helper\IncludeJquery
     */
    function testInvoke()
    {
        $unit = new IncludeJquery();
        $unit->setView(new PhpRenderer());
        $unit->__invoke();
    }
} 