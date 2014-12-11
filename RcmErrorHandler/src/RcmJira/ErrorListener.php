<?php

namespace RcmJira;

use RcmErrorHandler\EventManager\HandlerListenerBase;
use RcmErrorHandler\Format\FormatBase;
use RcmErrorHandler\Model\Config;
use RcmErrorHandler\Model\GenericError;
use RcmJira\Exception\JiraListenerException;

/**
 * Class ErrorListener
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJira
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ErrorListener extends HandlerListenerBase
{

    /**
     * @var \RcmErrorHandler\Model\Config
     */
    public $options;

    /**
     * @var \RcmErrorHandler\Model\Config
     */
    protected $listenerOptions;

    /**
     * @var JiraLogger $logger
     */
    protected $logger;

    /**
     * @param \RcmErrorHandler\Model\Config $options
     */
    public function __construct(
        \RcmErrorHandler\Model\Config $options,
        JiraLogger $logger
    ) {
        $this->options = $options;
        $this->listenerOptions = new Config($options->get('options', []));
        $this->logger = $logger;
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

        $logger = $this->logger;

        $extras = [
            'file' => $firstError->getFile(),
            'line' => $firstError->getLine(),
            'message' => $firstError->getMessage(),
            'includeServerDump' => $this->listenerOptions->get(
                'includeServerDump',
                false
            ),
            'includeSessionVars' => $this->listenerOptions->get(
                'includeSessionVars',
                false
            ),
        ];

        if ($this->listenerOptions->get('includeStacktrace', false) == true) {
            $extras['trace'] = $formatter->getTraceString($firstError);
        }

        $logger->log(
            $logger->getPriorityFromErrorNumber($firstError->getSeverity()),
            $this->prepareSummary($firstError),
            $extras
        );
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
}
