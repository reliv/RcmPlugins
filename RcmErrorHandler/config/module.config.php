<?php
return [
    'RcmErrorHandler' => [
        // enable Exception overrides (false = off)
        'overrideExceptions' => false,
        // enable Error overrides (false = off)
        'overrideErrors' => false,
        /**
         * Error formatters,
         * 'request/contentheader' => array(
         *   'class' => '\Some\Formater\Class',
         *   'options' => array('formatter' => 'options');
         * );
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
         * Listeners can be injected to log errors
         */
        'listener' => [
            /** EXAMPLES **/
            /**
             * Will only enter a issue if it does not find an existing one
             *
             *
            '\RcmJira\ErrorListener' => array(
                'event' => 'RcmErrorHandler::All',
                'options' => array(
                    // Issue will be entered in this project
                    'projectKey' => 'REF',
                    // Will not enter an issue if one is found in these projects
                    // (includes the project above)
                    'projectsToCheckForIssues' => array(
                        'ISS'
                    ),
                    // Will only enter an issue if one is not found in the projects
                    // that is NOT in one of the status below
                    'enterIssueIfNotStatus' => array(
                        'closed',
                        'resolved',
                    ),
                    // Include Stacktrace - true to include stacktrace
                    'includeStacktrace' => true,
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
                        // For example this would remove the number from
                        // exceptions like "requestId: 0678096"
                        '/requestId\:\s\d+/' => 'requestId: [see full desc for id]'
                    ]
                ),
            ),
            /* */
            /**
             * Standard logger for logging errors
             *
            '\RcmErrorHandler\Log\ErrorListener' => array(
                'event' => 'RcmErrorHandler::All',
                // \Zend\Log\Logger Options
                'options' => array(
                    'writers' => array(
                        array(
                            'name' => 'stream',
                            'priority' => null,
                            'options' => array(
                                'stream' => 'php://output', //'/www/logs/example.log'
                            ),
                        )
                    ),
                ),
            ),
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
    ],

    'service_manager' => [
        'factories' => [
            '\RcmErrorHandler\Config' => '\RcmErrorHandler\Factory\RcmErrorHandlerConfigFactory',
            '\RcmJira\Api' => '\RcmJira\Factory\JiraApiFactory',
            '\RcmJira\JiraLogger' => '\RcmJira\Factory\JiraLoggerFactory',
            '\RcmJira\ErrorListener' => '\RcmJira\Factory\ErrorListenerFactory',
            '\RcmErrorHandler\Log\ErrorListener' => '\RcmErrorHandler\Log\Factory\ErrorListenerFactory',
        ]
    ],
];
