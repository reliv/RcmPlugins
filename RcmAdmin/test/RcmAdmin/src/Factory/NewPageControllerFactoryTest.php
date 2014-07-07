<?php
/**
 * Test for Factory NewPageControllerFactory
 *
 * This file contains the test for the NewPageControllerFactory.
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

use RcmAdmin\Controller\NewPageController;
use RcmAdmin\Factory\NewPageControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory NewPageControllerFactory
 *
 * Test for Factory NewPageControllerFactory
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
class NewPageControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \RcmAdmin\Factory\NewPageControllerFactory
     */
    public function testCreateService()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $mockPageManager = $this->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockForm = $this->getMockBuilder('\Zend\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $formElementManager = new ServiceManager();
        $formElementManager->setService(
            'RcmAdmin\Form\NewPageForm',
            $mockForm
        );

        $serviceManager = new ServiceManager();
        $serviceManager->setService('Rcm\Service\SiteManager', $mockSiteManager);
        $serviceManager->setService('Rcm\Service\PageManager', $mockPageManager);
        $serviceManager->setService('FormElementManager', $formElementManager);

        $controllerManager = new ControllerManager();
        $controllerManager->setServiceLocator($serviceManager);

        $factory = new NewPageControllerFactory();
        $object = $factory->createService($controllerManager);

        $this->assertTrue($object instanceof NewPageController);
    }
}
