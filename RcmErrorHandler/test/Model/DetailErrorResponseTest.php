<?php
 /**
 * BasicErrorResponseTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Test\Model\BasicErrorResponse
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmErrorHandler\Test\Model;

require_once __DIR__ . '/../autoload.php';

use RcmErrorHandler\Model\DetailErrorResponse;

class DetailErrorResponseTest extends \PHPUnit_Framework_TestCase {

    public function test(){

        new DetailErrorResponse('TEst', 0);
    }
}
 