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
            /** EXAMPLE *
            '\RcmJira\ErrorListener' => array(
                'event' => 'RcmErrorHandler::ErrorHandler',
                'options' => array(
                    'endpoint' => 'https://devjira.reliv.com',
                    'username' => 'relivdomains',
                    'password' => 'relivjames01',
                    'projectKey' => 'REF',
                    'enterIssueIfNotStatus' => array(
                        'closed',
                        'resolved',
                    ),
                ),
            ),

            '\RcmErrorHandler\Log\ErrorListener' => array(
                'event' => 'RcmErrorHandler::ErrorHandler',
                // \Zend\Log\Logger Options
                'options' => array(
                    'writers' => array(
                        array(
                            'name' => 'stream',
                            'priority' => null,
                            'options' => array(
                                'stream' => '/www/_logs/rcm.log'
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
