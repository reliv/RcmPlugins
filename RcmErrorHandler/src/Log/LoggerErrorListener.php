<?php

namespace RcmErrorHandler\Log;

use RcmErrorHandler\EventManager\HandlerListenerBase;
use Zend\Log\Logger;

/**
 * Class LoggerErrorListener
 *
 * LoggerErrorListener
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class LoggerErrorListener extends HandlerListenerBase
{
    /**
     * @var array Error numbers to method
     */
    protected $loggerMethodMap = [
            Logger::EMERG => 'emerg',
            Logger::ALERT => 'alert',
            Logger::CRIT => 'crit',
            Logger::ERR => 'err',
            Logger::WARN => 'warn',
            Logger::NOTICE => 'notice',
            Logger::INFO => 'info',
            Logger::DEBUG => 'debug',
        ];

    /**
     * @var \RcmErrorHandler\Model\Config
     */
    protected $options;

    /**
     * @var array LoggerInterface $logger
     */
    protected $loggers;

    /**
     * @param \RcmErrorHandler\Model\Config $options
     */
    public function __construct(
        \RcmErrorHandler\Model\Config $options,
        $loggers
    ) {
        $this->options = $options;
        $this->loggers = $loggers;
    }

    /**
     * update
     *
     * @param \Zend\EventManager\Event $event
     *
     * @return void
     * @throws JiraListenerException
     */
    public function update(\Zend\EventManager\Event $event)
    {
        /** @var \RcmErrorHandler\Handler\Handler $handler */
        // $handler = $event->getParam('handler');

        /** @var \RcmErrorHandler\Model\GenericError $error */
        $error = $event->getParam('error');

        $firstError = $error->getFirst();

        /** @var \RcmErrorHandler\Model\Config $config */
        // $config = $event->getParam('config');

        $formatter = new FormatBase();

        $extras = [
            'file' => $firstError->getFile(),
            'line' => $firstError->getLine(),
            'message' => $firstError->getMessage(),
        ];

        if ($this->listenerOptions->get('includeStacktrace', false) == true) {
            $extras['trace'] = $formatter->getTraceString($firstError);
        }

        $loggers = $this->loggers;

        $method = $this->getMethodFromErrorNumber($firstError->getSeverity());

        $summary = $this->prepareSummary($firstError);

        foreach ($loggers as $logger) {

            $logger->$method(
                $summary,
                $extras
            );
        }
    }

    /**
     * prepareSummary
     *
     * @param GenericError $error
     *
     * @return string
     */
    public function prepareSummary(GenericError $error)
    {
        return $error->getType() . ' - ' .
        $error->getMessage() . ' - ' .
        $this->buildRelativePath($error->getFile());
    }

    /**
     * buildRelativePath
     *
     * @param $absoluteDir
     *
     * @return mixed
     */
    public function buildRelativePath($absoluteDir)
    {
        $relativeDir = $absoluteDir;

        $appDir = exec('pwd'); // or getcwd() could work if no symlinks are used

        $dirLength = strlen($appDir);

        if (substr($absoluteDir, 0, $dirLength) == $appDir) {

            $relativeDir = substr_replace($absoluteDir, '', 0, $dirLength);
        }

        return $relativeDir;
    }

    /**
     * getMethodFromErrorNumber
     *
     * @param $errno
     *
     * @return string
     */
    public function getMethodFromErrorNumber($errno)
    {
        $priority = Logger::INFO;

        if (isset(Logger::$errorPriorityMap[$errno])) {
            $priority = Logger::$errorPriorityMap[$errno];
        }

        $method = 'info';

        if (isset($this->loggerMethodMap[$priority])) {
            $method = $this->loggerMethodMap[$priority];
        }

        return $method;
    }
} 