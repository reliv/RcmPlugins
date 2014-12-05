<?php

namespace RcmErrorHandler\Handler;

use RcmErrorHandler\Model\Config;
use RcmErrorHandler\Model\GenericError;


/**
 * @codeCoverageIgnore - This has too many low level bits to test
 * Class HandlerAdapter
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category           Reliv
 * @package            RcmErrorHandler\Handler
 * @author             James Jervis <jjervis@relivinc.com>
 * @copyright          2014 Reliv International
 * @license            License.txt New BSD License
 * @version            Release: <package_version>
 * @link               https://github.com/reliv
 */
class Handler
{
    const EVENT_ALL = 'RcmErrorHandler::All';

    const EVENT_EXCEPTION = 'RcmErrorHandler::Exception';

    const EVENT_ERROR = 'RcmErrorHandler::Error';

    /**
     * @var Config $config
     */
    public $config;

    /**
     * @var \Zend\Mvc\MvcEvent $event
     */
    public $event;

    /**
     * @var array $errorMap
     */
    protected $errorMap
        = [
            E_ERROR => 'Error',
            E_PARSE => 'Parse',
            E_CORE_ERROR => 'CoreError',
            E_COMPILE_ERROR => 'CompileError',
            E_USER_ERROR => 'UserError',
            E_RECOVERABLE_ERROR => 'RecoverableError',
            E_STRICT => 'Strict',
            E_WARNING => 'Warning',
            E_CORE_WARNING => 'CoreWarning',
            E_COMPILE_WARNING => 'CompileWarning',
            E_USER_WARNING => 'UserWarning',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'UserDeprecated',
            E_NOTICE => 'Notice',
            E_USER_NOTICE => 'UserNotice',
            E_ALL => 'Unknown'
        ];

    /**
     * __construct
     *
     * @param Config             $config
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function __construct(
        Config $config,
        \Zend\Mvc\MvcEvent $event
    ) {
        $this->config = $config;
        $this->event = $event;
    }

    /**
     * getFormatter
     *
     * @return \RcmErrorHandler\Format\FormatInterface | null
     */
    public function getFormatter()
    {
        $format = $this->getFormat();

        $formatConfig = $this->config->get('format');

        if ($formatConfig) {
            if (isset($formatConfig[$format])) {
                return new $formatConfig[$format]['class'](
                    new Config($formatConfig[$format]['options'])
                );
            }
        }

        return null;
    }

    /**
     * getRequestHeaders
     *
     * @return array
     */
    protected function getRequestHeaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * getFormat
     *
     * @return null|string
     */
    protected function getFormat()
    {
        $headers = $this->getRequestHeaders();

        foreach ($headers as $type => $value) {
            if (stripos($type, 'Content-Type') !== false) {
                return trim($value);
            }
        }

        return '_default';
    }

    /**** ERROR ****/
    /**
     * handleError
     *
     * @param int    $errno
     * @param int    $errstr
     * @param string $errfile
     * @param int    $errline
     * @param array  $errcontext
     *
     * @return void
     */
    public function handleError(
        $errno = 0,
        $errstr = 1,
        $errfile = __FILE__,
        $errline = __LINE__,
        $errcontext = []
    ) {
        $prev = error_get_last();

        if ($prev !== null) {
            $prev = new GenericError(
                $prev['message'],
                0,
                $prev['type'],
                $prev['file'],
                $prev['line'],
                $this->getErrorType($prev['type'])
            );
        }

        $error = new GenericError(
            $errstr,
            0,
            $errno,
            $errfile,
            $errline,
            $this->getErrorType($errno),
            $prev,
            debug_backtrace(
                DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS,
                25
            )
        );

        $this->notify(self::EVENT_ERROR, $error);

        return $this->throwError($error);
    }

    /**
     * throwError
     *
     * @param GenericError $error
     *
     * @return bool
     */
    public function throwError(GenericError $error)
    {
        /** @var \RcmErrorHandler\Format\FormatInterface $formatter */
        $formatter = $this->getFormatter();

        if (!empty($formatter)) {

            if ($this->canDisplayErrors()
                && $this->canReportErrors(
                    $error->getSeverity()
                )
            ) {

                $formatter->displayString(
                    $error,
                    $this->event
                );

                return true;
            }
        }

        return $this->throwDefaultError();
    }

    /**
     * throwDefaultError
     *
     * @return bool
     */
    public function throwDefaultError()
    {
        restore_error_handler();

        return false;
    }

    /**
     * getErrorType
     *
     * @return string
     */
    public function getErrorType($errno)
    {
        $type = 'Error:';

        if (isset($this->errorMap[$errno])) {
            $type .= $this->errorMap[$errno];
        } else {
            $type .= GenericError::DEFAULT_TYPE;
        }

        return $type;
    }

    /**
     * canDisplayErrors
     *
     * @return string
     */
    public function canDisplayErrors()
    {
        return (boolean)(int)ini_get('display_errors');
    }

    /**
     * canReportErrors
     *
     * @return bool
     */
    public function canReportErrors($errno)
    {
        $reportingLevel = $this->getErrorReporting();

        return (($reportingLevel & $errno) > 0);
    }

    /**
     * getErrorReporting
     *
     * @return int
     */
    public function getErrorReporting()
    {
        return error_reporting();
    }

    /**
     * isFatalError
     *
     * @return bool
     */
    public function isFatalError()
    {
        $reportingLevel = (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING
            | E_COMPILE_ERROR | E_COMPILE_WARNING | E_STRICT);

        return ($reportingLevel & $this->errno);
    }


    /**** EXCEPTION ****/
    /**
     * handleException
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function handleException(
        \Exception $exception
    ) {
        $error = $this->buildErrorFromException($exception);

        $this->notify(self::EVENT_EXCEPTION, $error);

        return $this->throwException($error, $exception);
    }

    /**
     * throwException
     *
     * @param GenericError $error
     *
     * @return bool
     */
    public function throwException(GenericError $error, \Exception $exception)
    {
        /** @var \RcmErrorHandler\Format\FormatInterface $formatter */
        $formatter = $this->getFormatter();

        if (!empty($formatter)) {

            if ($this->canDisplayErrors()) {

                $formatter->displayString($error, $this->event);
                return true;
            } else {

                $formatter->displayBasicString($error, $this->event);
                return true;
            }
        }

        $this->throwDefaultException($exception);
    }

    /**
     * throwDefaultException
     *
     * @param \Exception $exception
     *
     * @return bool
     * @throws \Exception
     */
    public function throwDefaultException(\Exception $exception)
    {
        restore_exception_handler();

        throw $exception;
    }

    /**
     * getExceptionType
     *
     * @return string
     */
    public function getExceptionType($exception)
    {
        return 'Exception:' . get_class($exception);
    }

    /**
     * buildErrorFromException
     *
     * @param \Exception $exception
     *
     * @return GenericError
     */
    protected function buildErrorFromException(\Exception $exception)
    {
        $prev = $exception->getPrevious();

        if ($prev !== null) {
            $prev = $this->buildErrorFromException($prev);
        }

        $error = new GenericError(
            $exception->getMessage(),
            $exception->getCode(),
            E_ERROR,
            $exception->getFile(),
            $exception->getLine(),
            $this->getExceptionType($exception),
            $prev,
            $exception->getTrace()
        );

        return $error;
    }

    /**** EVENT EXCEPTION ****/
    /**
     * handleEventException
     *
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function handleEventException(
        \Zend\Mvc\MvcEvent $event
    ) {
        $exception = $event->getParam('exception');

        if (!$exception) {
            return;
        }

        $error = $this->buildErrorFromException($exception);

        $this->notify(self::EVENT_EXCEPTION, $error);

        return $this->throwException($error, $exception);
    }

    /**
     * notify
     *
     * @param string       $event
     * @param GenericError $error
     *
     * @return void
     */
    protected function notify($event, $error)
    {
        // Keep us from reporting suppressed errors
        if (!$this->canReportErrors($error->getSeverity())){
            return;
        }

        // Trigger Event
        $application = $this->event->getApplication();
        $em = $application->getEventManager();

        $em->trigger(
            $event,
            $this,
            [
                'handler' => $this,
                'error' => $error,
                'config' => $this->config
            ]
        );

        $em->trigger(
            self::EVENT_ALL,
            $this,
            [
                'handler' => $this,
                'error' => $error,
                'config' => $this->config
            ]
        );
    }
} 