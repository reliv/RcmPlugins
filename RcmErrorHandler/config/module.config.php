<?php
return [
    'RcmErrorHandler' => [
        // enable Exception overrides (false = off)
        'overrideExceptions' => false,
        // enable Error overrides (false = off)
        'overrideErrors' => false,
        /**
         * Error formatters,
         * 'request/contentheader' => [
         *   'class' => '\Some\Formater\Class',
         *   'options' => ['formatter' => 'options'];
         * ]
         */
        'format' => [
            /* Will over-ride system default if used *
            '_default' => array(
                'class' => '\RcmErrorHandler\Format\FormatDefault',
                'options' => array(),
            ),
            /* */
            // Used for JSON formating of errors if request is application/json
            'application/json' => [
                'class' => '\RcmErrorHandler\Format\FormatJson',
                'options' => [],
            ]
        ],
        /**
         * Listeners can be injected to listen for errors
         */
        'listener' => [
            /* Standard listener for logging errors using loggers *
            '\RcmErrorHandler\Log\LoggerErrorListener' => [
                // Required event
                'event' => 'RcmErrorHandler::All',
                // Options
                'options' => [
                    // Logger Services to use
                    'loggers' => [
                        'Reliv\RcmJira\Log\JiraLogger',
                        'Reliv\RcmAxosoft\Log\AxosoftLogger',
                    ],
                    // Include Stacktrace - true to include stacktrace for loggers
                    'includeStacktrace' => true,
                ],
            ],
            /* */
        ],
        /**
         * Define which loggers to use for JS logging
         */
        'jsLoggers' => [
            /* Use JiraLogger service *
            'Reliv\RcmJira\Log\JiraLogger',
            /* */
            /* Use AxosoftLogger service*
            'Reliv\RcmAxosoft\Log\AxosoftLogger',
            /* */
        ],
    ],
    'service_manager' => [
        'factories' => [
            '\RcmErrorHandler\Config' => '\RcmErrorHandler\Factory\RcmErrorHandlerConfigFactory',
            '\RcmErrorHandler\Log\LoggerErrorListener' => '\RcmErrorHandler\Log\Factory\LoggerErrorListenerFactory',
        ]
    ],
    'controllers' => [
        'invokables' => [
            'RcmErrorHandler\Controller\ApiClientErrorLoggerController'
            => 'RcmErrorHandler\Controller\ApiClientErrorLoggerController',
            'RcmErrorHandler\Controller\TestController'
            => 'RcmErrorHandler\Controller\TestController',
        ],
    ],
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'router' => [
        'routes' => [
            'RcmErrorHandler\ApiJsErrorLogger' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/rcm-error-handler/client-error[/:id]',
                    'defaults' => [
                        'controller' => 'RcmErrorHandler\Controller\ApiClientErrorLoggerController',
                    ],
                ],
            ],
            'RcmErrorHandler\TestError' => [
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/test/rcm-error-handler/error',
                    'defaults' => [
                        'controller' => 'RcmErrorHandler\Controller\TestController',
                        'action' => 'error',
                    ],
                ],
            ],
            'RcmErrorHandler\TestException' => [
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/test/rcm-error-handler/exception',
                    'defaults' => [
                        'controller' => 'RcmErrorHandler\Controller\TestController',
                        'action' => 'exception',
                    ],
                ],
            ],
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-error-handler/' => __DIR__ . '/../public/',
            ],
        ],
    ],
];
