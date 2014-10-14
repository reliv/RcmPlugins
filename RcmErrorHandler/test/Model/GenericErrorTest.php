<?php
 /**
 * GenericErrorTest.php
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

use RcmErrorHandler\Model\GenericError;

require_once __DIR__ . '/../autoload.php';

class GenericErrorTest extends \PHPUnit_Framework_TestCase {

    public function test(){

        $genericError1 = new GenericError(
            'MESSAGE1',
            0,
            E_ERROR,
            'FILE1',
            1,
            null
        );

        $genericError = new GenericError(
            'MESSAGE',
            0,
            E_ERROR,
            'FILE',
            1,
            GenericError::DEFAULT_TYPE,
            $genericError1
        );

        $message = $genericError->getMessage();

        $this->assertEquals('MESSAGE', $message);

        $code = $genericError->getCode();

        $this->assertEquals(0, $code);

        $severity = $genericError->getSeverity();

        $this->assertEquals(E_ERROR, $severity);

        $file = $genericError->getFile();

        $this->assertEquals('FILE', $file);

        $line = $genericError->getLine();

        $this->assertEquals(1, $line);

        $type = $genericError->getType();

        $this->assertEquals(GenericError::DEFAULT_TYPE, $type);

        $previous = $genericError->getPrevious();

        $this->assertEquals($genericError1, $previous);

        $first = $genericError->getFirst();

        $this->assertEquals($genericError1, $first);

        $errors = $genericError->getErrors($genericError);

        $this->assertTrue(is_array($errors));

        $trace = $genericError->getTrace(3, 2);

        $this->assertTrue(is_array($trace));

    }
}
 