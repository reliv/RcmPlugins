<?php
/**
 * Unit Test for the Admin Panel Controller
 *
 * This file contains the unit test for the Admin Panel Controller
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

use RcmAdmin\Controller\AdminPanelController;
use Zend\View\Model\ViewModel;


/**
 * Unit Test for the Admin Panel Controller
 *
 * Unit Test for the Admin Panel Controller
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class AdminPanelControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \RcmAdmin\Controller\AdminPanelController */
    protected $controller;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUserService;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->mockUserService = $this
            ->getMockBuilder('RcmUser\Service\RcmUserService')
            ->disableOriginalConstructor()
            ->getMock();

        $userService = $this->mockUserService;

        $config = $this->getConfig();

        /** @var \RcmUser\Service\RcmUserService $userService */
        $this->controller = new AdminPanelController(
            $config,
            $userService,
            1
        );
    }

    /**
     * Get admin panel config for tests
     *
     * @return array
     */
    protected function getConfig()
    {
        return array(
            'Page' => array(
                'display' => 'Page',
                'aclGroups' => 'admin',
                'cssClass' => '',
                'href' => '#',
                'links' => array(
                    'New' => array(
                        'display' => 'New',
                        'aclGroups' => 'admin',
                        'cssClass' => 'newPageIcon',
                        'href' => '#',
                        'links' => array(
                            'Page' => array(
                                'display' => 'Page',
                                'aclResource' => 'admin',
                                'aclPermissions' => 'page.new',
                                'cssClass' => 'rcmNewPageIcon rcmNewPage',
                                'href' => '#',
                                'data-title' => 'New Page',
                            ),
                        )
                    ),


                    'Edit' => array(
                        'display' => 'Edit',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array(
                            'Page' => array(
                                'display' => 'Edit Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditPageIcon rcmEditPage',
                                'href' => '#',
                            ),
                            'Page Layout' => array(
                                'display' => 'Add/Remove Plugins on Page',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmLayoutIcon rcmShowLayoutEditor',
                                'href' => '#',
                            ),

                            'Page Properties' => array(
                                'display' => 'Page Properties',
                                'aclGroups' => 'admin',
                                'cssClass' => 'PagePropertiesIcon rcmPageProperties',
                                'href' => '#',
                            ),
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * Test the constructor is working
     *
     * @return void
     * @covers RcmAdmin\Controller\AdminPanelController::__construct
     */
    public function testConstructor()
    {
        $this->assertTrue($this->controller instanceof AdminPanelController);
    }


    /**
     * Test getAdminWrapperAction
     *
     * @return void
     * @covers RcmAdmin\Controller\AdminPanelController::getAdminWrapperAction
     */
    public function testGetAdminWrapperAction()
    {
        $this->mockUserService->expects($this->once())
            ->method('isAllowed')
            ->with(
                $this->equalTo('sites.1'),
                $this->equalTo('admin'),
                $this->equalTo('Rcm\Acl\ResourceProvider')
            )->will($this->returnValue(true));

        /** @var ViewModel $result */
        $result = $this->controller->getAdminWrapperAction();

        $this->assertTrue($result instanceof ViewModel);

        $expected = $this->getConfig();

        $actual = $result->getVariable('adminMenu');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test getAdminWrapperAction User not Allowed to see admin panel
     *
     * @return void
     * @covers RcmAdmin\Controller\AdminPanelController::getAdminWrapperAction
     */
    public function testGetAdminWrapperActionNotAllowed()
    {
        $this->mockUserService->expects($this->once())
            ->method('isAllowed')
            ->with(
                $this->equalTo('sites.1'),
                $this->equalTo('admin'),
                $this->equalTo('Rcm\Acl\ResourceProvider')
            )->will($this->returnValue(false));

        $result = $this->controller->getAdminWrapperAction();

        $this->assertNull($result);
    }

}