<?php
/**
 * Test for Factory NewPageFormFactory
 *
 * This file contains the test for the NewPageFormFactory.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use RcmAdmin\Factory\NewPageFormFactory;
use RcmAdmin\Form\NewPageForm;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory NewPageFormFactory
 *
 * Test for Factory NewPageFormFactory
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class NewPageFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \RcmAdmin\Factory\NewPageFormFactory
     */
    public function testCreateService()
    {
        $mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            'Rcm\Service\PageManager',
            $mockPageManager
        );

        $serviceManager->setService(
            'Rcm\Service\LayoutManager',
            $mockLayoutManager
        );

        $formManager = new FormElementManager();
        $formManager->setServiceLocator($serviceManager);

        $factory = new NewPageFormFactory();
        $object = $factory->createService($formManager);

        $this->assertTrue($object instanceof NewPageForm);
    }
}
