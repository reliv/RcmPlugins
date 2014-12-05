<?php
/**
 * ModuleTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\Test
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmLogin\Test;

use RcmLogin\Module;

require_once __DIR__ . '/autoload.php';

class ModuleTest extends \PHPUnit_Framework_TestCase
{

    protected function buildMocks()
    {

        // \Zend\Http\Request getQuery
        $mockObject = $this->getMockBuilder(
            '\Zend\Http\Request'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockRequest = $mockObject->getMock();
        $this->mockRequest->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue(1));

        // \RcmUser\Service\RcmUserService clearIdentity
        $mockObject = $this->getMockBuilder(
            '\RcmUser\Service\RcmUserService'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockRcmUserService = $mockObject->getMock();
        $this->mockRcmUserService->expects($this->any())
            ->method('clearIdentity')
            ->will($this->returnValue(true));

        // \Zend\ServiceManager\ServiceManager
        $mapServiceManager = [
            ['request', true, $this->mockRequest],
            [
                'RcmUser\Service\RcmUserService',
                true,
                $this->mockRcmUserService
            ]
        ];
        $mockObject = $this->getMockBuilder(
            '\Zend\ServiceManager\ServiceManager'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockServiceManager = $mockObject->getMock();
        $this->mockServiceManager->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($mapServiceManager));

        // \Zend\EventManager\EventManager
        $mockObject = $this->getMockBuilder(
            '\Zend\EventManager\EventManager'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockEventManager = $mockObject->getMock();
        $this->mockEventManager->expects($this->any())
            ->method('attach');

        // \Zend\Mvc\Application
        $mockObject = $this->getMockBuilder(
            '\Zend\Mvc\Application'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockApplication = $mockObject->getMock();
        $this->mockApplication->expects($this->any())
            ->method('getServiceManager')
            ->will($this->returnValue($this->mockServiceManager));
        $this->mockApplication->expects($this->any())
            ->method('getEventManager')
            ->will($this->returnValue($this->mockEventManager));

        // \Zend\Mvc\MvcEvent
        $mockObject = $this->getMockBuilder(
            '\Zend\Mvc\MvcEvent'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockMvcEvent = $mockObject->getMock();
        $this->mockMvcEvent->expects($this->any())
            ->method('getApplication')
            ->will($this->returnValue($this->mockApplication));


        // \Zend\ModuleManager\ModuleManager
    }

    public function testGetAutoloaderConfig()
    {
        $module = new Module();

        $result = $module->getAutoloaderConfig();

        $this->assertTrue(is_array($result), 'Did not return array.');
    }

    public function testGetConfig()
    {
        $module = new Module();

        $result = $module->getConfig();

        $this->assertTrue(is_array($result), 'Did not return array.');
    }
}
 