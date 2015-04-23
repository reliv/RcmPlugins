<?php

namespace RcmErrorHandler\Test;

use RcmErrorHandler\Model\Config;
use RcmErrorHandler\Model\GenericError;

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

    public function getMockServiceLocator()
    {
        $mock = $this->getMockBuilder(
            '\Zend\ServiceManager\ServiceLocatorInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects(
            $this->any()
        )
            ->method('has')
            ->will(
                $this->returnValue(true)
            );

        $mock->expects(
            $this->any()
        )
            ->method('get')
            ->will(
                $this->returnValue($this->getMockLogger())
            );

        return $mock;
    }

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

    public function getMockGenericError($errno = 8)
    {
        $error = new GenericError(
            'TEST MESSAGE1',
            111,
            $errno,
            '/test/file1.php',
            123,
            'TEST:TYPE1',
            null
        );

        $error2 = new GenericError(
            'TEST MESSAGE2',
            222,
            2,
            '/test/file2.php',
            234,
            'TEST:TYPE2',
            $error
        );

        $mock = $this->getMockBuilder(
            '\RcmErrorHandler\Model\GenericError'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects(
            $this->any()
        )
            ->method('getMessage')
            ->will(
                $this->returnValue('TEST MESSAGE3')
            );

        $mock->expects(
            $this->any()
        )
            ->method('getCode')
            ->will(
                $this->returnValue(333)
            );

        $mock->expects(
            $this->any()
        )
            ->method('getSeverity')
            ->will(
                $this->returnValue($errno)
            );

        $mock->expects(
            $this->any()
        )
            ->method('getFile')
            ->will(
                $this->returnValue('/test/file3.php')
            );

        $mock->expects(
            $this->any()
        )
            ->method('getLine')
            ->will(
                $this->returnValue(345)
            );

        $mock->expects(
            $this->any()
        )
            ->method('getType')
            ->will(
                $this->returnValue('TEST:TYPE3')
            );

        $mock->expects(
            $this->any()
        )
            ->method('getPrevious')
            ->will(
                $this->returnValue($error2)
            );

        $mock->expects(
            $this->any()
        )
            ->method('getFirst')
            ->will(
                $this->returnValue($error)
            );

        $mock->expects(
            $this->any()
        )
            ->method('getErrors')
            ->will(
                $this->returnValue(['TEST3'])
            );

        $mock->expects(
            $this->any()
        )
            ->method('getTrace')
            ->will(
                $this->returnValue($this->getMockBacktrace())
            );

        return $mock;
    }

    public function getMockBacktrace()
    {

        $mockObject = new \stdClass();

        $mockObject->test = 'TEST';

        $mockStack = [
            [
                'file' => '/mock/test1.php',
                'line' => 1,
                'function' => 'mockFunction',
                'class' => 'Some\Controller1',
                'object' => $mockObject,
                'type' => '->',
                'args' => [
                    [],
                    $mockObject,
                    'mockArg'
                ],
            ],
            [
                'file' => '/mock/test2.php',
                'line' => 2,
                'function' => 'mockFunction',
                'class' => 'Some\Controller2',
                'object' => $mockObject,
                'type' => '->',
                'args' => [
                    [],
                    $mockObject,
                    'mockArg'
                ],
            ],
        ];

        return $mockStack;
    }

    public function getMockMvcEvent($errno = 8)
    {
        $mock = $this->getMockBuilder(
            '\Zend\Mvc\MvcEvent'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects(
            $this->any()
        )
            ->method('getResponse')
            ->will(
                $this->returnValue($this->getMockHttpResponse())
            );

        $mock->expects(
            $this->any()
        )
            ->method('getParam')
            ->will(
                $this->returnValueMap(
                    [
                        [
                            'error',
                            null,
                            $this->getMockGenericError($errno)
                        ],
                    ]
                )
            );

        return $mock;
    }

    public function getMockHttpResponse($content = '{"test": "json"}')
    {

        $mock = $this->getMockBuilder(
            '\Zend\Http\Response'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects(
            $this->any()
        )
            ->method('getHeaders')
            ->will(
                $this->returnValue($this->getMockHttpHeaders())
            );
        $mock->expects(
            $this->any()
        )
            ->method('getContent')
            ->will(
                $this->returnValue($content)
            );

        return $mock;
    }

    public function getMockHttpHeaders()
    {

        $mock = $this->getMockBuilder(
            '\Zend\Http\Headers'
        )
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    public function getMockConfig()
    {

        $rcmErrorHandler = [
            'overrideExceptions' => true,
            'overrideErrors' => true,
            'format' => [
                '_default' => '\RcmErrorHandler\Format\FormatDefault',
                'application/json' => [
                    'class' => '\RcmErrorHandler\Format\FormatJson',
                    'options' => [],
                ]
            ],

            'listener' => [
                '\RcmErrorHandler\Log\LoggerErrorListener' => [
                    // Required event
                    'event' => 'RcmErrorHandler::All',
                    // Options
                    'options' => [
                        // Logger Services to use
                        'loggers' => [
                            'RcmJira\Log\JiraLogger',
                        ],
                        // Include Stacktrace - true to include stacktrace
                        'includeStacktrace' => true,
                    ],
                ],
            ],
        ];

        return new Config($rcmErrorHandler);
    }


    public function getMockLoggerListenerOptions()
    {

        return new Config(
            [
                'loggers' => [
                    '\Some\Logger',
                ],
                // Include Stacktrace - true to include stacktrace
                'includeStacktrace' => true,
            ]
        );
    }

    public function getMockLogger()
    {

        $mock = $this->getMockBuilder(
            '\Zend\Log\Logger'
        )
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
} 