<?php

namespace RcmInstanceConfigTest\Entity;

require_once __DIR__ . '/../../../../../Rcm/test/Base/BaseTestCase.php';

use RcmInstanceConfig\ViewHelper\RcmEdit;
use RcmTest\Base\BaseTestCase;

class RcmEditTest extends BaseTestCase
{

    const DIRTY_CONTENT = '<h1 onhover="alert(1)">aloha!</h1>';
    const CLEAN_CONTENT = '<h1>aloha!</h1>';
    const IC_KEY = 'salutation';

    public function setUp()
    {
        $this->addModule('RcmInstanceConfig');
        parent::setUp();
    }

    /**
     * @covers \RcmInstanceConfig\ViewHelper\RcmEdit
     */
    public function testInvokeTextEditFromInstanceConfig()
    {
        $edit = new RcmEdit($this->getMockPurifier(), false);
        $edit->setView(
            $this->getMockView(array(self::IC_KEY => self::DIRTY_CONTENT))
        );
        $this->assertEquals(
            '<p data-textEdit="salutation" class="bigFunky"><h1>aloha!</h1></p>',
            $edit->__invoke(
                self::IC_KEY, '<b>defaultContent<b>', 'p',
                array('class' => 'bigFunky')
            )
        );
    }

    /**
     * @covers \RcmInstanceConfig\ViewHelper\RcmEdit
     */
    public function testInvokeTextEditFromWithoutInstanceConfig()
    {
        $edit = new RcmEdit($this->getMockPurifier(), false);
        $edit->setView( $this->getMockView(array()));
        $this->assertEquals(
            '<p data-textEdit="salutation" class="bigFunky"><h1>aloha!</h1></p>',
            $edit->__invoke(
                self::IC_KEY, self::DIRTY_CONTENT, 'p',
                array('class' => 'bigFunky')
            )
        );
    }

    /**
     * @covers \RcmInstanceConfig\ViewHelper\RcmEdit
     */
    public function testInvokeRichEditFromInstanceConfig()
    {
        $edit = new RcmEdit($this->getMockPurifier(), true);
        $edit->setView(
            $this->getMockView(array(self::IC_KEY => self::DIRTY_CONTENT))
        );
        $this->assertEquals(
            '<p data-richEdit="salutation" class="bigFunky"><h1>aloha!</h1></p>',
            $edit->__invoke(
                self::IC_KEY, '<b>defaultContent<b>', 'p',
                array('class' => 'bigFunky')
            )
        );
    }

    function getMockPurifier()
    {
        $purifier = $this->getMockBuilder('\HTMLPurifier')->getMock();
        $purifier->expects($this->once())
            ->method('purify')
            ->will($this->returnValue(self::CLEAN_CONTENT));
        return $purifier;
    }

    function getMockView($instanceConfig)
    {
        $view = $this->getMockBuilder('\Zend\View\Renderer\RendererInterface')
            ->getMock();
        $view->instanceConfig = $instanceConfig;
        return $view;
    }
} 