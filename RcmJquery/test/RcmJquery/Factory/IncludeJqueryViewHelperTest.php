<?php
/**
 * IncludeJqueryViewHelperTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery\test\RcmJquery\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmJqueryTest;

use RcmJquery\Factory\IncludeJquery;
use RcmJquery\Factory\IncludeJqueryFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';


/**
 * IncludeJqueryViewHelperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery\test\RcmJquery\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeJqueryViewHelperTest extends BaseTestCase
{
    function setup()
    {
        $this->addModule('RcmJquery');
        parent::setup();
    }

    /**
     * @covers RcmJquery\Factory\IncludeJqueryFactory
     */
    function testCreateService()
    {
        $unit = new IncludeJqueryFactory();
        $serviceLocator = new ServiceManager();
        $this->assertInstanceOf(
            'RcmJquery\View\Helper\IncludeJquery',
            $unit->createService($serviceLocator)
        );
    }
} 