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
        'RcmDynamicNavigation' => [
            'type' => 'Common',
            'display' => 'Dynamic Navigation Menu',
            'tooltip' => 'An editable navigation menu',
            'icon' => '',
            'canCache'=> false,
            'editJs' => '/modules/rcm-dynamic-navigation/edit.js',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'controllers' => [
        'factories' => [
            'RcmDynamicNavigation' => '\RcmDynamicNavigation\Factory\PluginControllerFactory',
        ]
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-dynamic-navigation/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-dynamic-navigation/edit.js',
                ],
            ],
        ],
    ],
];