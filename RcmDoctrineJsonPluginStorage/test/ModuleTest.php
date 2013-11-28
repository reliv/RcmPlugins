<?php


namespace RcmDoctrineJsonPluginStorageTest;


use RcmDoctrineJsonPluginStorage\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDoctrineJsonPluginStorage\Module */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    /**
     * No covers tag so this tests both class map file and module file
     */
    public function testGetAutoloaderConfig()
    {
        $autoLoadConfig = $this->module->getAutoloaderConfig();
        $mapPath = array_pop($autoLoadConfig['Zend\Loader\ClassMapAutoloader']);
        $this->assertTrue(is_array(include($mapPath)));
    }

    /**
     * No covers tag here in order to test both config file and module file
     */
    public function testGetConfig()
    {
        $this->assertTrue(is_array($this->module->getConfig()));
    }
} 