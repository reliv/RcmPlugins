<?php
namespace RcmSocialButtons\Controller;



class PluginControllerTest extends \Rcm\Base\BaseTest
{
    /**
     * @var PluginController
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Rcm\Controller\PluginInterface
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
     * @covers Rcm\Controller\PluginInterface::saveInstance
     * @covers RcmSocialButtons\Controller\PluginController::saveInstance
     */
    function testSaveAction(){
        $em=$this->getEmMock();

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $this->unit->setEm($em);

        $this->unit->saveInstance(42,'<p>hi</p>');
    }
}
