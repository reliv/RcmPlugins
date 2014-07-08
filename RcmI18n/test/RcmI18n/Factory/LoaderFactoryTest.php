<?php

namespace RcmI18nTest\RemoteLoader;

use RcmI18n\Factory\LoaderFactory;
use Zend\I18n\Translator\LoaderPluginManager;
use Zend\ServiceManager\ServiceManager;

require __DIR__ . '/../../autoload.php';

class LoaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers RcmI18n\Factory\LoaderFactory
     */
    function testCreateService()
    {
        $sm = new ServiceManager();
        $sm->setService(
            'Doctrine\ORM\EntityManager',
            $this->getMockBuilder('Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock()
        );
        $loadPluginMgr = new LoaderPluginManager();
        $loadPluginMgr->setServiceLocator($sm);
        $unit = new LoaderFactory();
        $this->assertInstanceOf(
            'RcmI18n\RemoteLoader\DoctrineDbLoader',
            $unit->createService($loadPluginMgr)
        );
    }
}