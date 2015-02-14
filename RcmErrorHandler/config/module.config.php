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
                        '\RcmJira\JiraLogger',
                    ],
                    // Include Stacktrace - true to include stacktrace
                    'includeStacktrace' => true,
                ],
            ],
            /* */
        ],

        /**
         * Define which loggers to use for JS logging
         */
        'jsLoggers' => [
            /* Use JiraLogger *
            '\RcmJira\JiraLogger',
            /* */
        ],
    ],

    /**
     * Configuration for JIRA API
     */
    'RcmJira' => [
        'api' => [
            'endpoint' => 'https://jira.example.com',
            'username' => 'myUsername',
            'password' => 'myPassword',
        ],
        'JiraLoggerOptions' => [
            /* Options */

            // Issue will be entered in this project
            'projectKey' => 'REF',

            // Will not enter an issue if one is found in these projects
            // (includes the project above)
            'projectsToCheckForIssues' => [
                //'ISS' => 'ISS'
            ],

            // Will only enter an issue if one is not found in the projects
            // that is NOT in one of the status below
            'enterIssueIfNotStatus' => [
                'closed' => 'closed',
                'resolved' => 'resolved',
            ],

            // Include dump of server vars - true to include server dump
            'includeServerDump' => true,

            // WARNING: this can be a security issue
            // Set to an array of specific session keys to display or 'ALL' to display all
            'includeSessionVars' => false,

            // This is useful for preventing exceptions who have dynamic
            // parts from creating multipule entries in JIRA
            // Jira ticket descriptions will be ran through preg_replace
            // using these as the preg_replace arguments.
            'summaryPreprocessors' => [
                // $pattern => $replacement
            ]
            /* */
        ],
    ],

    'service_manager' => [
        'factories' => [
            '\RcmErrorHandler\Config' => '\RcmErrorHandler\Factory\RcmErrorHandlerConfigFactory',
            '\RcmErrorHandler\Log\LoggerErrorListener' => '\RcmErrorHandler\Log\Factory\LoggerErrorListenerFactory',
            '\RcmJira\Api' => '\RcmJira\Factory\JiraApiFactory',
            '\RcmJira\JiraLogger' => '\RcmJira\Factory\JiraLoggerFactory',
        ]
    ],

    'controllers' => [
        'invokables' => [
            'RcmErrorHandler\Controller\ApiClientErrorLoggerController'
            => 'RcmErrorHandler\Controller\ApiClientErrorLoggerController',
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
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-error-handler/' => __DIR__ . '/../public/',
            ],
        ],
    ],
//    'view_helpers' => [
//        'invokables' => [
//            'headscript' => 'RcmErrorHandler\ViewHelper\HeadScriptWithErrorHandlerFirst',
//        ]
//    ]
];
