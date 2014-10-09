<?php

namespace RcmErrorHandler\Test\Format;

use RcmErrorHandler\Test\Mocks;

require_once __DIR__ . '/../Mocks.php';

class FormatDefaultTest extends Mocks {

    /** @var  \RcmErrorHandler\Format\FormatDefault */
    public $formatter;

    public function setup()
    {
        $this->formatter = new \RcmErrorHandler\Format\FormatDefault();
    }

    public function testGetErrorString() {

        $res = $this->formatter->getErrorString($this->getMockErrorHandler());

        $this->assertTrue(is_string($res));
    }

}
 