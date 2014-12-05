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
        'RcmTabs' => [
            'type' => 'Content Templates',
            'display' => 'Tabbed Container',
            'tooltip' => 'A tabbed plugin controller.',
            'icon' => '',
            'editJs' => '/modules/rcm-tabs/edit.js',
            'editCss' => '/modules/rcm-tabs/edit-style.css',
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
                'modules/rcm-tabs/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-tabs/edit.js',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmTabs' => 'RcmTabs\Factory\BaseControllerFactory'
        ]
    ]

];