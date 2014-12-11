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
        'RcmEventListDisplay' => [
            'type' => 'Common',
            'display' => 'Event List Display',
            'tooltip' => 'Displays the list of events in a given event category',
            'icon' => '',
            'editJs' => '/modules/rcm-event-list-display/rcm-event-list-display-edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'
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
                'modules/rcm-event-list-display/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-event-list-display/rcm-event-list-display-edit.js',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmEventListDisplay' => 'RcmEventListDisplay\Factory\PluginControllerFactory'
        ]
    ]
];