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

    public function getMockExceptionHandler()
    {
        /** @var \RcmErrorHandler\Handler\ExceptionHandler mockExceptionHandler */
        $mockExceptionHandler = $this->getMockBuilder(
            '\RcmErrorHandler\Handler\ExceptionHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $mockExceptionHandler->expects(
            $this->any()
        )
            ->method('getException')
            ->will(
                $this->returnValue(new \Exception('TEST'))
            );
        return $mockExceptionHandler;
    }

    public function getMockErrorHandler()
    {
        /** @var \RcmErrorHandler\Handler\ErrorHandler mockErrorHandler */
        $mockErrorHandler = $this->getMockBuilder(
            '\RcmErrorHandler\Handler\ErrorHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $mockErrorHandler->expects(
            $this->any()
        )
            ->method('getErrstr')
            ->will(
                $this->returnValue('TEST')
            );
        $mockErrorHandler->expects(
            $this->any()
        )
            ->method('getErrno')
            ->will(
                $this->returnValue(999)
            );
        $mockErrorHandler->expects(
            $this->any()
        )
            ->method('getErrfile')
            ->will(
                $this->returnValue('\mock\test.php')
            );
        $mockErrorHandler->expects(
            $this->any()
        )
            ->method('getErrline')
            ->will(
                $this->returnValue(333)
            );

        return $mockErrorHandler;
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