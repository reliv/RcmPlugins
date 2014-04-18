<?php
/**
 * Test file
 *
 * Test file
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfigTest\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmInstanceConfigTest\Factory;

use RcmInstanceConfig\Factory\HtmlPurifierFactory;
use RcmInstanceConfig\Factory\RcmRichEditFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

/**
 * Test file
 *
 * Test file
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfigTest\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmRichEditFactoryTest extends BaseTestCase
{
    function testCreateService()
    {
        $factory = new RcmRichEditFactory();
        $serviceMgr = new ServiceManager();
        $serviceMgr->setService(
            'RcmInstanceConfig\HtmlPurifier', $this->getMock('\HTMLPurifier')
        );
        $helperMgr = new HelperPluginManager();
        $helperMgr->setServiceLocator($serviceMgr);
        $this->assertInstanceOf(
            'RcmInstanceConfig\ViewHelper\RcmEdit',
            $factory->createService($helperMgr)
        );
    }
} 