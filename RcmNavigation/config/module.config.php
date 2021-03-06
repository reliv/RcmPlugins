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
        'RcmNavigation' => [
            'type' => 'Common',
            'display' => 'Navigation Menu',
            'tooltip' => 'Navigation menu that can display sub-menus when users mouse-over the main menu.',
            'icon' => '',
            'editJs' => '/modules/rcm-navigation/edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php',
            'canCache'=> true
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
                'modules/rcm-navigation/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/modules.css' => [
                    'modules/rcm-navigation/style.css',
                ],
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-navigation/edit.js',
                ],
            ],
        ],
    ],
];