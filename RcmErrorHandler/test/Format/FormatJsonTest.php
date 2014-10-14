<?php
 /**
 * FormatJsonTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Test\Format
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Test\Format;

use RcmErrorHandler\Format\FormatJson;
use RcmErrorHandler\Test\Mocks;

require_once __DIR__ . '/../Mocks.php';

class FormatJsonTest extends Mocks {

    public function testSetGet(){

        $formater = new FormatJson();

        $error = $this->getMockGenericError();

        $event = $this->getMockMvcEvent();

        $string = $formater->getString($error);

        $this->assertTrue(is_string($string));

        $basicString = $formater->getBasicString($error);

        $this->assertTrue(is_string($basicString));

        $traceString = $formater->getTraceString($error, 3, 1);

        $this->assertTrue(is_string($traceString));

        /* CANNOT TEST due to exit()

        $formater->displayString($error, $event);

        $formater->displayBasicString($error, $event);

        $formater->displayTraceString($error, $event);

        */
    }
}
 