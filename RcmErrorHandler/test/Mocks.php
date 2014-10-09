<?php

namespace RcmErrorHandler\Test;

require_once __DIR__ . '/autoload.php';

/**
 * Class Mocks
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Test
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Mocks extends \PHPUnit_Framework_TestCase
{

    public function getHandler()
    {
        /** @var \RcmErrorHandler\Handler\ExceptionHandler mockExceptionHandler */
        $mock = $this->getMockBuilder(
            '\RcmErrorHandler\Handler\Handler'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects(
            $this->any()
        )
            ->method('getFormatter')
            ->will(
                $this->returnValue(null)
            );
        return $mock;
    }

    public function getMockBacktrace()
    {

        $mockObject = new stdClass();

        $mockObject->test = 'TEST';

        $mockStack = array(
            array(
                'file' => '/mock/test.php',
                'line' => 26,
                'function' => 'mockFunction',
                'class' => 'RcmLogin\Controller\PluginController',
                'object' => $mockObject,
                'type' => '->',
                'args' => array(
                    array(),
                    $mockObject,
                    'mockArg'
                ),
            ),
            array(
                'file' => '/mock/test.php',
                'line' => 26,
                'function' => 'mockFunction',
                'class' => 'RcmLogin\Controller\PluginController',
                'object' => $mockObject,
                'type' => '->',
                'args' => array(
                    array(),
                    $mockObject,
                    'mockArg'
                ),
            ),
        );

        return $mockStack;
    }
} 