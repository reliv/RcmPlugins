<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 */
return [

    'rcmPlugin' => [
        'RcmActionButton' => [
            'type' => 'Content Templates',
            'display' => 'Action Button',
            'tooltip' => 'Action box with editible text',
            'icon' => '',
            'editJs' => '/modules/rcm-action-button/rcm-action-button-edit.js',
            'defaultInstanceConfig' => include
                __DIR__ . '/defaultInstanceConfig.php',
            'canCache' => true,
            'colorSwatches' => [
                '#000000' => 'Black',
                '#DDDDDD' => 'Light Gray',
                '#FFFFFF' => 'White',
                '#00B2B0' => 'Teal',
                '#00A5DB' => 'Blue',
                '#8DC63F' => 'Green',
                '#F15D22' => 'Red',
                '#F8971D' => 'Orange'
            ],
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
                'modules/rcm-action-button/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                // required for admin edit //
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-action-button/rcm-action-button-edit.js',
                ],
            ],
        ],
    ],
];