<?php

namespace RcmErrorHandler\Test\EventManager;

require_once __DIR__ . '/../Mocks.php';

use RcmErrorHandler\EventManager\HandlerListenerBase;
use RcmErrorHandler\Model\Config;
use RcmErrorHandler\Test\Mocks;

class HandlerListenerBaseTest extends Mocks {

    public function test(){

        $options = new Config([]);

        $listener = new HandlerListenerBase($options);

        $listener->update($this->getMockMvcEvent());
    }
}
 