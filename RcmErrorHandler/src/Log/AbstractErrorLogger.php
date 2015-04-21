<?php

namespace RcmErrorHandler\Log;

use Zend\Log\Logger;
use Zend\Log\LoggerInterface;

/**
 * Class AbstractErrorLogger
 *
 * AbstractErrorLogger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

abstract class AbstractErrorLogger implements LoggerInterface
{
    /**
     * @var array $priorities
     */
    protected $priorities
        = [
            Logger::EMERG => 'EMERG',
            Logger::ALERT => 'ALERT',
            Logger::CRIT => 'CRIT',
            Logger::ERR => 'ERR',
            Logger::WARN => 'WARN',
            Logger::NOTICE => 'NOTICE',
            Logger::INFO => 'INFO',
            Logger::DEBUG => 'DEBUG',
        ];

    protected $options = [];

    /**
     * getOption
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return null
     */
    protected function getOption($key, $default = null)
    {
        if (isset($this->options[$key])) {

            return $this->options[$key];
        }

        return $default;
    }

    /**
     * getPriorityString
     *
     * @param $priority
     *
     * @return string
     */
    public function getPriorityString($priority)
    {
        if (isset($this->priorities[$priority])) {
            $priorityString = $this->priorities[$priority];
        } else {
            $priorityString = $this->priorities[Logger::INFO];
        }
        return $priorityString;
    }

    /**
     * getPriorityFromErrorNumber
     *
     * @param $errno
     *
     * @return int
     */
    public function getPriorityFromErrorNumber($errno)
    {
        if (isset(Logger::$errorPriorityMap[$errno])) {
            $priority = Logger::$errorPriorityMap[$errno];
        } else {
            $priority = Logger::INFO;
        }
        return $priority;
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function emerg($message, $extra = [])
    {
        $this->log(Logger::EMERG, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function alert($message, $extra = [])
    {
        $this->log(Logger::ALERT, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function crit($message, $extra = [])
    {
        $this->log(Logger::CRIT, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function err($message, $extra = [])
    {
        $this->log(Logger::ERR, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function warn($message, $extra = [])
    {
        $this->log(Logger::WARN, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function notice($message, $extra = [])
    {
        $this->log(Logger::NOTICE, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function info($message, $extra = [])
    {
        $this->log(Logger::INFO, $message, $extra);
    }

    /**
     * @param string            $message
     * @param array|Traversable $extra
     *
     * @return LoggerInterface
     */
    public function debug($message, $extra = [])
    {
        $this->log(Logger::DEBUG, $message, $extra);
    }

    /**
     * Add a message as a log entry
     *
     * @param  int               $priority
     * @param  mixed             $message
     * @param  array|Traversable $extra
     *
     * @return Logger
     */
    public function log($priority, $message, $extra = [])
    {
        // NULL LOGGER - Extend and Over-ride this

        return $this;
    }

    /**
     * getDescription
     *
     * @param array $extra
     *
     * @return string
     */
    protected function getDescription($extra = [])
    {

        $description = '';

        if (isset($extra['description'])) {
            $description .= $extra['description'];
        }

        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
            $description .= "\n HOST: " . $_SERVER['HTTP_HOST'];
        }

        if (isset($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
            $description .= "\n URL: " . $_SERVER['REQUEST_URI'];
        }

        if (isset($extra['file'])) {
            $description .= "\n File: " . $extra['file'];
        }

        if (isset($extra['line'])) {
            $description .= "\n Line: " . $extra['line'];
        }

        if (isset($extra['message'])) {
            $description .= "\n Message: " . $extra['message'];
        }

        if (isset($extra['trace'])) {
            $description .= "\n Stack trace: \n" . $extra['trace'];
        }

        $includeServerDump = $this->getOption('includeServerDump', false);

        if (isset($_SERVER) && $includeServerDump) {
            $description .= "\n" . $this->prepareArray('Server', $_SERVER);
        }

        $includeSessionVars = $this->getOption('includeSessionVars', null);

        if (isset($_SESSION) && !empty($includeSessionVars)) {

            $description .= "\n" . $this->prepareSession(
                    $includeSessionVars
                );
        }

        return $description;
    }

    /**
     * prepareSummary
     *
     * @param $priority
     * @param $message
     *
     * @return string
     */
    protected function prepareSummary($priority, $message)
    {

        $preprocessors = $this->getOption('summaryPreprocessors', []);

        foreach ($preprocessors as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        $summary = $this->getPriorityString($priority) . ': ' . $message;

        $summary = substr($summary, 0, 255);

        $summary = str_replace(
            [
                "\r",
                "\n"
            ],
            ' ',
            $summary
        );

        return $summary;
    }

    /**
     * prepareSession
     *
     * @param $includeSessionVars
     *
     * @return string
     */
    protected function prepareSession($includeSessionVars)
    {
        $sessionVars = [];

        if (is_array($includeSessionVars)) {
            $sessionVarKeys = $includeSessionVars;
            foreach ($sessionVarKeys as $key) {

                if (isset($_SESSION[$key])) {
                    $sessionVars[$key] = $_SESSION[$key];
                }
            }
        }

        if ($includeSessionVars == 'ALL') {
            $sessionVars = $_SESSION;
        }

        return $this->prepareArray('Session', $sessionVars);
    }

    /**
     * prepareArray
     *
     * @todo - Might implement recursive for array
     *
     * @param $name
     * @param $array
     *
     * @return string
     */
    protected function prepareArray($name, $array)
    {
        $output = $name . ": \n";

        foreach ($array as $key => $val) {

            if (is_string($val)) {

                $output .= ' - ' . $key . ' = "' . $val . "\"\n";
            } elseif (is_numeric($val)) {

                $output .= ' - ' . $key . ' = ' . $val . "\n";
            } elseif (is_null($val)) {

                $output .= ' - ' . $key . " = NULL\n";
            } elseif (is_bool($val)) {

                $output
                    .= ' - ' . $key . ' = ' . $val ? 'TRUE' : 'FALSE' . "\n";
            } else {

                $output .= ' - ' . $key . ' = (' . gettype($val) . ") \n" .
                    '{code}' . print_r($val, true) . "{code}\n";
            }
        }

        return $output;
    }

}