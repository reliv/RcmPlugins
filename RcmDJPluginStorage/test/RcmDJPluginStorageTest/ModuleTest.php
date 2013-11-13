<?php


namespace RcmDjPluginStorageTest;


use RcmDjPluginStorage\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDjPluginStorage\Module */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * @covers \RcmDjPluginStorage\Module
     */
    public function testGetAutoloaderConfig()
    {
        $this->assertTrue(is_array($this->module->getAutoloaderConfig()));
    }

    /**
     * No covers tag here in order to test both config file and module file
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }
} 