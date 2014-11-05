<?php
return array(
    'RcmErrorHandler' => array(
        'overrideExceptions' => false,
        'overrideErrors' => false,
        'format' => array(
            /* Will over-ride system default if used *
            '_default' => array(
                'class' => '\RcmErrorHandler\Format\FormatDefault',
                'options' => array(),
            ),
            /* */
            'application/json' => array(
                'class' => '\RcmErrorHandler\Format\FormatJson',
                'options' => array(),
            )
        ),

        'listener' => array(
            /** EXAMPLE **
            '\RcmJira\ErrorListener' => array(
                'event' => 'RcmErrorHandler::All',
                'options' => array(
                    'projectKey' => 'REF',
                    'enterIssueIfNotStatus' => array(
                        'closed',
                        'resolved',
                    ),
                ),
            ),
            /* *
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
        ),
    ),

    'RcmJira' => array(
        'api' => array(
            'endpoint' => 'https://jira.example.com',
            'username' => 'myUsername',
            'password' => 'myPassword',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            '\RcmErrorHandler\Config' => '\RcmErrorHandler\Factory\RcmErrorHandlerConfigFactory',
            '\RcmJira\Api' => '\RcmJira\Factory\JiraApiFactory',
            '\RcmJira\JiraLogger' => '\RcmJira\Factory\JiraLoggerFactory',
            '\RcmJira\ErrorListener' => '\RcmJira\Factory\ErrorListenerFactory',
            '\RcmErrorHandler\Log\ErrorListener' => '\RcmErrorHandler\Log\Factory\ErrorListenerFactory',
        )
    ),
);
