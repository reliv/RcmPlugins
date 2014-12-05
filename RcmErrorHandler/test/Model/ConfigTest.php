<?php
 /**
 * ConfigTest.php
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

use RcmErrorHandler\Model\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {

    public function test(){

        $configArr = ['test' => 'testvalue'];

        $config = new Config($configArr);

        $test = $config->get('test');

        $this->assertEquals('testvalue', $test);

        $nope = $config->get('nope');

        $this->assertEquals(null, $nope);

        $not = $config->get('nope', 'not');

        $this->assertEquals('not', $not);

        $all = $config->getAll();

        $this->assertEquals($all, $configArr);


    }
}
 