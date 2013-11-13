<?php


namespace RcmDJPluginStorageTest;


use RcmDJPluginStorage\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDJPluginStorage\Module */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * @covers \RcmDJPluginStorage\Module
     */
    public function testGetAutoloaderConfig()
    {
        $this->assertTrue(is_array($this->module->getAutoloaderConfig()));
    }

    /**
     * No covers tag here in order to test the config file and the module file
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }
} 