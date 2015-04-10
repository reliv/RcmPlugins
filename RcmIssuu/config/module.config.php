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

    'rcmPlugin' => [
        'RcmIssuu' => [
            'type' => 'Common',
            'display' => 'Issuu',
            'tooltip' => 'Embed an Issuu document',
            'icon' => '',
            'editJs' => '/modules/rcm-issuu/edit.js',
            'canCache'=> false
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-issuu/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-issuu/edit.js',
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'RcmIssuu\Service\IssuuApi' => 'RcmIssuu\Service\IssuuApi',
        ]
    ],

    'controllers' => [
        'factories' => [
            'RcmIssuu' => '\RcmIssuu\Factory\PluginControllerFactory',
        ],
    ],
];
