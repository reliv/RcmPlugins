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
        'RcmMessageList' => [
            'type' => 'Common',
            'display' => 'Message List Display',
            'tooltip' => 'Displays a list of user messages ordered by importance and date.',
            'icon' => '',
            'canCache'=> false,
            'defaultInstanceConfig' => include
                __DIR__ . '/defaultInstanceConfig.php',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-message-list/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-message-list/rcm-message-list-edit.js',
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
