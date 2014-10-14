<?php

namespace RcmErrorHandler\Test\Format;

use RcmErrorHandler\Format\FormatDefault;
use RcmErrorHandler\Test\Mocks;

require_once __DIR__ . '/../Mocks.php';

class FormatDefaultTest extends Mocks {

    public function testSetGet(){

        $formater = new FormatDefault();

        $error = $this->getMockGenericError();

        $string = $formater->getString($error);

        $this->assertTrue(is_string($string));

        $traceString = $formater->getTraceString($error, 3, 1);

        $this->assertTrue(is_string($traceString));
    }

}
 