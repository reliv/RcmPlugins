<?php
return array(
    'RcmErrorHandler' => array(
        'overrideExceptions' => true,
        'overrideErrors' => true,
        'format' => array(
            //'_default' => '\RcmErrorHandler\Format\FormatDefault',
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
                    'endpoint' => 'https://jira.example.com',
                    'username' => 'myUsername',
                    'password' => 'myPassword',
                    'projectKey' => 'REF',
                    'enterIssueIfNotStatus' => array(
                        'closed',
                        'resolved',
                    ),
                ),
            ),

            '\RcmErrorHandler\Log\ErrorListener' => array(
                'event' => 'RcmErrorHandler::All',
                // \Zend\Log\Logger Options
                'options' => array(
                    'writers' => array(
                        array(
                            'name' => 'stream',
                            'priority' => null,
                            'options' => array(
                                'stream' => '/www/logs/example.log'
                            ),
                        )
                    ),
                ),
            ),
            /* */
        ),
    ),

    'service_manager' => array(
        'factories' => array()
    ),
);
