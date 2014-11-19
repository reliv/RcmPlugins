<?php

namespace RcmErrorHandler\Factory;

use RcmErrorHandler\Handler\Handler;
use RcmErrorHandler\Model\Config;

/**
 * Class HandlerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmErrorHandler\Handler
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmErrorHandlerFactory
{
    /**
     * @var Config $config
     */
    protected $config = null;

    /**
     * @var Handler $handler
     */
    protected $handler = null;

    /**
     * __construct
     *
     * @param Config $config
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function __construct(
        Config $config,
        \Zend\Mvc\MvcEvent $event
    ) {
        $this->config = $config;
        $this->event = $event;
        $this->buildHandlers();
    }

    /**
     * getConfig
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * getHandler
     *
     * @return Handler
     */
    public function getHandler()
    {
        if (!$this->handler) {

            $this->handler = new Handler(
                $this->config,
                $this->event
            );
        }

        return $this->handler;
    }

    /**
     * buildHandlers
     *
     * @return void
     */
    public function buildHandlers()
    {
        $this->buildExceptionHandler();
        $this->buildErrorHandler();
    }

    /**
     * buildErrorHandler
     *
     * @return void
     */
    public function buildErrorHandler()
    {
        $config = $this->getConfig();

        if (!$config->get('overrideErrors')) {
            return;
        }

        $errorHandler = $this->getHandler();

        $originalHandler = set_error_handler(
            array(
                $errorHandler,
                'handleError'
            )
        );

        /* @todo Should this be done? *
         * register_shutdown_function(
         * function ($errorHandler) {
         *
         * $err = error_get_last();
         *
         * $errorHandler->handle(
         * $err['type'],
         * $err['message'],
         * $err['file'],
         * $err['line'],
         * array('SHUT')
         * );
         * return true;
         * },
         * $errorHandler
         * );
         * /* */
    }

    /**
     * buildExceptionHandler
     *
     * @return void
     */
    public function buildExceptionHandler()
    {
        $config = $this->getConfig();

        if (!$config->get('overrideExceptions')) {
            return;
        }

        $exceptionHander = $this->getHandler();

        $originalHandler = set_exception_handler(
            array(
                $exceptionHander,
                'handleException'
            )
        );
    }

    /**
     * buildListeners
     *
     * @param $eventManager
     *
     * @return void
     */
    public function buildListeners($eventManager, $serviceLocator)
    {
        $config = $this->getConfig();

        $listenersConfig = $config->get('listener');

        if (!$listenersConfig) {
            return;
        }

        foreach ($listenersConfig as $class => $listenerConfig) {

            if($serviceLocator->has($class) && !empty($listenerConfig['event'])) {

                $obj = $serviceLocator->get($class);

                $eventManager->attach(
                    $listenerConfig['event'],
                    array(
                        $obj,
                        'update'
                    )
                );
            }
        }
    }
} 