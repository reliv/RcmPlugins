<?php
/**
 * Unit Test for the Admin Page Controller
 *
 * This file contains the unit test for the Admin Page Controller
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
namespace RcmAdminTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use RcmAdmin\Controller\NewPageController;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Unit Test for the Admin Page Controller
 *
 * Unit Test for the Admin Page Controller
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class NewPageControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Controller\IndexController */
    protected $controller;

    /** @var \Zend\Http\Request */
    protected $request;

    /** @var \Zend\Http\Response */
    protected $response;

    /** @var \Zend\Mvc\Router\RouteMatch */
    protected $routeMatch;

    /** @var \Zend\Mvc\MvcEvent */
    protected $event;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUserService;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageManager;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageForm;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockGetUser;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockRedirectToPage;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUser;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->mockUserService = $this
            ->getMockBuilder('\RcmUser\Controller\Plugin\RcmUserIsAllowed')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockUser = $this
            ->getMockBuilder('RcmUser\User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockGetUser = $this
            ->getMockBuilder('\RcmUser\Controller\Plugin\RcmUserGetCurrentUser')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockGetUser->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue($this->mockUser));

        $this->mockRedirectToPage = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\UrlToPage')
            ->getMock();

        $this->mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageForm = $this
            ->getMockBuilder('\Zend\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();


        $config = array(
            'RcmAdmin\Page\New' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/rcm-admin/page/new',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'new',
                    ),
                ),
            ),
        );

        $mockPageManager = $this->mockPageManager;
        $mockPageForm = $this->mockPageForm;

        /** @var \Rcm\Service\PageManager $mockPageManager */
        /** @var \RcmAdmin\Form\NewPageForm $mockPageForm */
        $this->controller = new NewPageController(
            $mockPageManager,
            $mockPageForm,
            1
        );

        $this->controller->getPluginManager()
            ->setService('rcmUserIsAllowed', $this->mockUserService)
            ->setService('rcmUserGetCurrentUser', $this->mockGetUser)
            ->setService('urlToPage', $this->mockRedirectToPage);

        $this->request = new Request();
        $this->routeMatch = new RouteMatch(
            array(
                'controller' => 'RcmAdmin\Controller\PageController'
            )
        );
        $this->event = new MvcEvent();
        $routerConfig = $config;
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    /**
     * Test the constructor is working
     *
     * @return void
     * @covers RcmAdmin\Controller\NewPageController::__construct
     */
    public function testConstructor()
    {
        $this->assertTrue($this->controller instanceof NewPageController);
    }

    /**
     * Test New Action User not Allowed
     *
     * @return void
     * @covers RcmAdmin\Controller\NewPageController::newAction
     */
    public function testNewActionUserNotAllowed()
    {
        $this->mockUserService->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue(false));

        $this->routeMatch->setParam('action', 'new');

        /** @var \Rcm\Http\Response $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($result instanceof \Rcm\Http\Response);

        $this->assertEquals(401, $result->getStatusCode());
    }

    /**
     * Test New Action Returns Form
     *
     * @return void
     * @covers RcmAdmin\Controller\NewPageController::newAction
     */
    public function testNewAction()
    {
        $this->mockUserService->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setValidationGroup')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setData')
            ->will($this->returnValue(true));

        $this->routeMatch->setParam('action', 'new');

        /** @var \Zend\View\Model\ViewModel $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($result instanceof ViewModel);

        $this->assertTrue($result->getVariable('form') instanceof Form);
    }

    /**
     * Test New Action Saves Form
     *
     * @return void
     * @covers RcmAdmin\Controller\NewPageController::newAction
     */
    public function testNewActionSavePage()
    {

        $formParams = array(
            'page-template' => null,
            'main-layout' => 'someLayout',
            'url' => 'my-test',
            'title' => 'someTitle'
        );

        $this->mockUserService->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setValidationGroup')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setData')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($formParams));

        $this->mockUser->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Westin Shafer'));

        $this->mockPageManager->expects($this->once())
            ->method('createNewPage')
            ->with(
                $this->equalTo('my-test'),
                $this->equalTo('someTitle'),
                $this->equalTo('someLayout'),
                $this->equalTo('Westin Shafer')
            );

        $this->mockRedirectToPage->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->equalTo('my-test'),
                $this->equalTo('n')
            )->will($this->returnValue('/my-test'));

        $this->routeMatch->setParam('action', 'new');


        $params = new Parameters;
        $params->fromArray($formParams);

        $this->request->setPost($params);
        $this->request->setMethod('POST');

        /** @var \Zend\View\Model\ViewModel $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($result instanceof JsonModel);

        $this->assertEquals('/my-test', $result->getVariable('redirect'));
    }

    /**
     * Test New Action Form Invalid
     *
     * @return void
     * @covers RcmAdmin\Controller\NewPageController::newAction
     */
    public function testNewActionFormInvalid()
    {
        $this->mockUserService->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setValidationGroup')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->once())
            ->method('setData')
            ->will($this->returnValue(true));

        $this->mockPageForm->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->mockPageForm->expects($this->never())
            ->method('getData');

        $this->mockPageForm->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue('SomeError'));

        $this->mockUser->expects($this->never())
            ->method('getName');

        $this->mockPageManager->expects($this->never())
            ->method('createNewPage');

        $this->mockRedirectToPage->expects($this->never())
            ->method('__invoke');

        $this->routeMatch->setParam('action', 'new');

        $params = new Parameters;
        $params->fromArray(array('my-post' => 'nothingValid'));

        $this->request->setPost($params);
        $this->request->setMethod('POST');

        /** @var \Zend\View\Model\ViewModel $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertTrue($result instanceof ViewModel);

        $this->assertEquals('SomeError', $result->getVariable('errors'));
    }


}