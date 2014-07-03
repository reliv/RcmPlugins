<?php
/**
 * Test for Factory AdminPanelControllerFactory
 *
 * This file contains the test for the AdminPanelControllerFactory.
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

use RcmAdmin\Controller\AdminPanelController;
use RcmAdmin\Factory\AdminPanelControllerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory AdminPanelControllerFactory
 *
 * Test for Factory AdminPanelControllerFactory
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
class AdminPanelControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \RcmAdmin\Factory\AdminPanelControllerFactory
     */
    public function testCreateService()
    {
        $config = array(
            'rcmAdmin' => array(
                'adminPanel' => array(
                    'Page' => array(
                        'display' => 'Page',
                        'aclGroups' => 'admin',
                        'cssClass' => '',
                        'href' => '#',
                    ),
                )
            ),
        );

        $mockUserService = $this
            ->getMockBuilder('\RcmUser\Service\RcmUserService')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager = $this
            ->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            'RcmUser\Service\RcmUserService',
            $mockUserService
        );

        $serviceManager->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );

        $serviceManager->setService(
            'config',
            $config
        );

        $factory = new AdminPanelControllerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof AdminPanelController);
    }

    /**
     * Generic test for the create service.  Continues even with no config
     *
     * @return null
     * @covers \RcmAdmin\Factory\AdminPanelControllerFactory
     */
    public function testCreateServiceNoPanelConfig()
    {
        $config = array(
            'rcmAdmin' => array(),
        );

        $mockUserService = $this
            ->getMockBuilder('\RcmUser\Service\RcmUserService')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager = $this
            ->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            'RcmUser\Service\RcmUserService',
            $mockUserService
        );

        $serviceManager->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );

        $serviceManager->setService(
            'config',
            $config
        );

        $factory = new AdminPanelControllerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof AdminPanelController);
    }
}