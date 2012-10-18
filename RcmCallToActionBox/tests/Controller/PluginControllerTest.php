<?php
namespace RcmCallToActionBox\Controller;



class PluginControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PluginController
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Rcm\Controller\PluginControllerInterface
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
     * @covers Rcm\Controller\PluginControllerInterface::saveAction
     * @covers RcmCallToActionBox\Controller\PluginController::saveAction
     */
    function testGetDefaultJsonContent(){

        $this->unit->getDefaultJsonContent();

        $this->markTestIncomplete();
    }
}
