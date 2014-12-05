<?php

namespace RcmI18nTest\RemoteLoader;

use RcmI18n\RemoteLoader\DoctrineDbLoader;
use RcmI18nTest\Mock\Mock;

require __DIR__ . '/../../autoload.php';
require __DIR__ . '/../Mock/Mock.php';

class DoctrineDbLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers RcmI18n\RemoteLoader\DoctrineDbLoader
     */
    function testLoad()
    {
        $query = new Mock();
        $query->setMethod('setParameter', $query);
        $query->setMethod(
            'getArrayResult',
            [
                [
                    'defaultText' => 'translate',
                    'text' => 'Translatadoralata'
                ]
            ]
        );
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();
        $entityMgr->expects($this->any())->method('createQuery')->will(
            $this->returnValue($query)
        );
        $unit = new DoctrineDbLoader($entityMgr);
        $textDomain = $unit->load('en_US');
        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $textDomain);
        $this->assertEquals(
            ['translate' => 'Translatadoralata'],
            $textDomain->getArrayCopy()
        );
    }
} 