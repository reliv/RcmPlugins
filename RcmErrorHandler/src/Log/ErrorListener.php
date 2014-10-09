<?php

namespace RcmErrorHandler\Log;

use RcmErrorHandler\EventManager\HandlerListenerBase;
use Zend\Log\Logger;

/**
 * Class ErrorListener
 *
 * LongDescHere
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
class ErrorListener extends HandlerListenerBase
{
    /**
     * update
     *
     * @param \Zend\EventManager\Event $event
     *
     * @return mixed|void
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

        $logger = new Logger($this->options->getAll());

        $message = ' | type: ' . $firstError->getType() .
            ' | message: ' . $firstError->getMessage() .
            ' | file: ' . $firstError->getFile() .
            ' | line: ' . $firstError->getLine();

        $logger->log(
            $this->getPriorityFromErrorNumber($firstError->getSeverity()),
            $message
        );
    }

    /**
     * getPriorityFromErrorNumber
     *
     * @param int $errno
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
} 