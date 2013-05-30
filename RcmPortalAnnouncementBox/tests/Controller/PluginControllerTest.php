<?php
namespace RcmPortalAnnouncementBox\Controller;



class PluginControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PluginController
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Rcm\Plugin\PluginInterface
     */
    protected function setUp()
    {
        $this->unit = new PluginController();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Rcm\Plugin\PluginInterface::saveInstance
     * @covers RcmPortalAnnouncementBox\Controller\PluginController::saveInstance
     */
    function testGetDefaultJsonContent(){

        $this->unit->defaultInstanceConfigRepo->getInstanceConfig();

        $this->markTestIncomplete();
    }
}
