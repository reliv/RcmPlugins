<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.4
 *
 * LICENSE: New BSD License
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return [
    'router' => [
        'routes' => [
            'rcm-number-format-http-api-currency' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-number-format-http-api/currency/:value',
                    'defaults' => [
                        'controller' => 'NumberFormatController',
                        'action' => 'currency',
                    ]
                ],
            ],
            'rcm-number-format-http-api-number' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-number-format-http-api/number/:value',
                    'defaults' => [
                        'controller' => 'NumberFormatController',
                        'action' => 'number',
                    ]
                ],
            ],
        ]
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-number-format/' => __DIR__ . '/../public/',
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'NumberFormatController' => 'RcmNumberFormat\Factory\NumberFormatControllerFactory'
        ]
    ],
];