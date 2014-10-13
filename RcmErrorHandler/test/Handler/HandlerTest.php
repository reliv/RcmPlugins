<?php
 /**
 * HandlerTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Test\Handler\Handler
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Test\Handler;

require_once __DIR__ . '/../Mocks.php';

use RcmErrorHandler\Handler\Handler;
use RcmErrorHandler\Test\Mocks;

class HandlerTest extends Mocks {

    public function test(){

        $hander = new Handler($this->getMockConfig(), $this->getMockMvcEvent());
    }
}
 