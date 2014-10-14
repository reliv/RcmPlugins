<?php
 /**
 * ErrorListenerTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Test\Log\ErrorListener
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Test\Log;

require_once __DIR__ . '/../Mocks.php';

use RcmErrorHandler\Log\ErrorListener;
use RcmErrorHandler\Test\Mocks;

class ErrorListenerTest extends Mocks {

    public function test(){

        $logger = $this->getMockLogger();

        $listener = new ErrorListener($this->getMockLogListenerConfig(), $logger);

        $listener->update($this->getMockMvcEvent(0));

        $listener->update($this->getMockMvcEvent(E_NOTICE));
    }
}
 