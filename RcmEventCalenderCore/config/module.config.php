<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return [

    'router' => [
        'routes' => [
            'rcm-event-calender-core-event' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-event-calender/events[/:id]',
                    'defaults' => [
                        'controller' => 'EventAPIController',
                    ]
                ],
            ],
            'rcm-event-calender-core-category' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-event-calender/categories[/:id]',
                    'defaults' => [
                        'controller' => 'CategoryAPIController',
                    ]
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'doctrine' => [
        'driver' => [
            'RcmEventCalenderCore' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/RcmEventCalenderCore/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'RcmEventCalenderCore' => 'RcmEventCalenderCore'
                ]
            ]
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-event-calender-core/' => __DIR__ . '/../public/',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmCalendarCore' => 'RcmCalendarCore\Factory\CalendarModelFactory'
        ]
    ],
    'controllers' => [
        'factories' => [
            'EventAPIController' =>
                'RcmEventCalendarCore\Factory\EventAPIControllerFactory',
            'CategoryAPIController' =>
                'RcmEventCalendarCore\Factory\CategoryAPIControllerFactory'
        ]
    ]
];