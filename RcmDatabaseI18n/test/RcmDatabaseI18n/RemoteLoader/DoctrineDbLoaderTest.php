<?php

namespace RcmDatabaseI18nTest\RemoteLoader;

use RcmDatabaseI18n\RemoteLoader\DoctrineDbLoader;

require __DIR__ . '/../../autoload.php';

class DoctrineDbLoaderTest extends \PHPUnit_Framework_TestCase
{
    function testLoad()
    {
        $query = $this->getMockBuilder('Doctrine\ORM\Query');
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityMgr->expects($this->any())->method('createQuery')->willReturn($query);
        $unit = new DoctrineDbLoader($entityMgr);
        $textDomain = $unit->load('en_US');
        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $textDomain);
    }
} 