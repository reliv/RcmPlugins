<?php

namespace RcmI18nTest\RemoteLoader;

use RcmI18n\Entity\Message;

require __DIR__ . '/../../autoload.php';

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Message
     */
    protected $unit;

    function setup()
    {
        $this->unit = new Message();
    }

    /**
     * @covers RcmI18n\Entity\Message
     */
    public function testSetGetKey()
    {
        $value = 'Bob';
        $this->unit->setDefaultText($value);
        $this->assertEquals($value, $this->unit->getDefaultText());
    }

    /**
     * @covers RcmI18n\Entity\Message
     */
    public function testSetGetLocale()
    {
        $value = 'en_US';
        $this->unit->setLocale($value);
        $this->assertEquals($value, $this->unit->getLocale());
    }

    /**
     * @covers RcmI18n\Entity\Message
     */
    public function testSetGetText()
    {
        $value = 'Bobinseo';
        $this->unit->setText($value);
        $this->assertEquals($value, $this->unit->getText());
    }
} 