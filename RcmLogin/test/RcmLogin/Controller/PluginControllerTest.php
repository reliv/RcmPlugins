<?php
/**
 * PluginControllerTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\Test\RcmLogin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmLogin\Test\RcmLogin\Controller;

use RcmLogin\Controller\PluginController;
use RcmUser\User\Entity\User;
use Zend\Authentication\Result;

require_once __DIR__ . '/../../autoload.php';

class PluginControllerTest extends \PHPUnit_Framework_TestCase
{

    protected function buildMocks($result, $user = null)
    {
        // \RcmInstanceConfig\Service\PluginStorageMgr
        $instanceConfig = [
            'translate' => [
                'missing' => 'missing',
                'invalid' => 'invalid',
                'systemFailure' => 'systemFailure',

            ],
        ];
        $mockObject = $this->getMockBuilder(
            '\RcmInstanceConfig\Service\PluginStorageMgr'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockPluginStorageMgr = $mockObject->getMock();
        $this->mockPluginStorageMgr->expects($this->any())
            ->method('getInstanceConfig')
            ->will($this->returnValue($instanceConfig));

        // config
        $this->mockConfig = [
            'Rcm' => [
                'successfulLoginUrl' => 'someurl'
            ]
        ];


        // \Zend\Http\Request getQuery
        $mapRequest = [
            ['username', null, 'testusername'],
            ['password', null, 'testpassword']
        ];
        $mockObject = $this->getMockBuilder(
            '\Zend\Http\Request'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockRequest = $mockObject->getMock();
        $this->mockRequest->expects($this->any())
            ->method('getPost')
            ->will($this->returnValueMap($mapRequest));

        // \RcmUser\Service\RcmUserService
        $mockObject = $this->getMockBuilder(
            '\RcmUser\Service\RcmUserService'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockRcmUserService = $mockObject->getMock();
        $this->mockRcmUserService->expects($this->any())
            ->method('buildNewUser')
            ->will($this->returnValue($user));
        $this->mockRcmUserService->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($result));


        // \Zend\Mvc\Controller\PluginManager
        $mapPluginManager = [
            ['request', true, $this->mockRequest],
            [
                'RcmUser\Service\RcmUserService',
                true,
                $this->mockRcmUserService
            ]
        ];
        $mockObject = $this->getMockBuilder(
            '\Zend\Mvc\Controller\PluginManager'
        );
        $mockObject->disableOriginalConstructor();
        $this->mockPluginManager = $mockObject->getMock();
        $this->mockPluginManager->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($mapPluginManager));


    }

    public function getMockResult(
        $code = Result::SUCCESS,
        $identity = null,
        $messages = []
    ) {

        return new Result($code, $identity, $messages);
    }

    public function testRenderInstance()
    {
        $user = new User('123');
        $result = $this->getMockResult();
        $this->buildMocks($result, $user);

        $controller
            = new PluginController( $this->mockConfig, $this->mockRcmUserService);

        $result = $controller->renderInstance(1,[]);

        // @todo this controller has some bits tat need to be refactored before unit testing
        // $this->assertTrue(is_array($result), 'Array not returned');
    }


}
 