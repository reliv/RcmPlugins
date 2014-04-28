<?php

namespace RcmDatabaseI18nTest\RemoteLoader;

use RcmDatabaseI18n\Entity\Message;

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

    public function testSetGetKey()
    {
        $value='Bob';
        $this->unit->setKey($value);
        $this->assertEquals($value, $this->unit->getKey());
    }

    public function testSetGetLocale()
    {
        $value='en_US';
        $this->unit->setLocale($value);
        $this->assertEquals($value, $this->unit->getLocale());
    }

    public function testSetGetText()
    {
        $value='Bobinseo';
        $this->unit->setText($value);
        $this->assertEquals($value, $this->unit->getText());
    }
} 