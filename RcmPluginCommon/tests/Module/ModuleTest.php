<?php
namespace RcmPluginCommon\Module;

class ModuleTest extends \Rcm\Base\BaseTest
{
    /**
     * @var Module
     */
    protected $unit;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->unit = new \RcmPluginCommon\Module();
        $GLOBALS['em'] = $this->getEmMock();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($GLOBALS['em']);
    }

    /**
     * @covers RcmPluginCommon\Module
     */
    public function testgetServiceConfig()
    {
        $config = $this->unit->getServiceConfig();

        $this->assertInternalType('array', $config);
    }
}
