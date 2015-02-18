<?php

namespace RcmErrorHandler\Test\Format;

use RcmErrorHandler\Format\FormatBase;
use RcmErrorHandler\Test\Mocks;

require_once __DIR__ . '/../Mocks.php';

class FormatBaseTest extends Mocks
{

    public function testSetGet(){

        $formater = new FormatBase();

        $error = $this->getMockGenericError();

        $event = $this->getMockMvcEvent();

        $string = $formater->getString($error);

        $this->assertTrue(is_string($string));

        $basicString = $formater->getBasicString($error);

        $this->assertTrue(is_string($basicString));

        $traceString = $formater->getTraceString($error, 3, 1);

        $this->assertTrue(is_string($traceString));

        //ob_start();

//        $formater->displayString($error, $event);
//
//        $formater->displayBasicString($error, $event);
//
//        $formater->displayTraceString($error, $event);

        //ob_clean();
    }
}
 