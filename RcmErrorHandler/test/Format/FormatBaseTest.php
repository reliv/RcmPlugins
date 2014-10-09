<?php

namespace RcmErrorHandler\Test\Format;

use RcmErrorHandler\Test\Mocks;

require_once __DIR__ . '/../Mocks.php';

class FormatBaseTest extends Mocks
{
    /** @var  \RcmErrorHandler\Format\FormatBase */
    public $formatBase;

    public function setup()
    {
        $this->formatBase = new \RcmErrorHandler\Format\FormatBase();
    }

    public function testGetExceptionString()
    {
        $res = $this->formatBase->getExceptionString($this->getMockExceptionHandler());

        $this->assertTrue(is_string($res));
    }

    public function testGetErrorString()
    {
        $res = $this->formatBase->getErrorString($this->getMockErrorHandler());

        $this->assertEquals('TEST', $res);

        $this->assertTrue(is_string($res));
    }

    public function testGetSimpleString()
    {
        $res = $this->formatBase->getSimpleString($this->getMockErrorHandler());

        $this->assertTrue(is_string($res));
    }

    public function testFormatBacktrace()
    {
        $res = $this->formatBase->formatBacktrace($this->getMockBacktrace(), 1);

        $this->assertTrue(is_string($res));
    }

    public function testOther()
    {
        $this->formatBase->finalExceptionAction();

        $this->formatBase->finalErrorAction();
    }
}
 