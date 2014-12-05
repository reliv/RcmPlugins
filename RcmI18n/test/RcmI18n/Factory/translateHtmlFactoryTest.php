<?php

namespace RcmI18nTest\ViewHelper;

use RcmI18n\Factory\TranslateHtmlFactory;
use Zend\I18n\Translator\LoaderPluginManager;
use Zend\ServiceManager\ServiceManager;

require __DIR__ . '/../../autoload.php';

class TranslateHtmlFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers RcmI18n\Factory\TranslateHtmlFactory
     */
    function testCreateService()
    {
        $sm = new ServiceManager();
        $sm->setService(
            'RcmHtmlPurifier',
            $this->getMockBuilder('\HtmlPurifier')
                ->disableOriginalConstructor()
                ->setMethods(['purify'])
                ->getMock()
        );
        $viewSm = new LoaderPluginManager();
        $viewSm->setServiceLocator($sm);
        $unit = new TranslateHtmlFactory();
        $this->assertInstanceOf(
            'RcmI18n\ViewHelper\TranslateHtml',
            $unit->createService($viewSm)
        );
    }
}