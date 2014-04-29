<?php
/**
 * Test file
 *
 * Test file
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmHtmlPurifierTest\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmHtmlPurifierTest\Factory;

use RcmHtmlPurifier\Factory\HtmlPurifierFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;

require_once __DIR__ . '/../../autoload.php';

/**
 * Test file
 *
 * Test file
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmHtmlPurifierTest\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class HtmlPurifierFactoryTest extends \PHPUnit_Framework_TestCase
{
    function testCreateService()
    {
        $factory = new HtmlPurifierFactory();
        $this->assertInstanceOf(
            '\HTMLPurifier', $factory->createService(new ServiceManager())
        );
    }
} 