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
        'RcmThreeChoices' => [
            'type' => 'Content Templates',
            'display' => 'Three Choices',
            'tooltip' => 'Three Choices! Which will you choose?',
            'icon' => '',
            'editJs' => '/modules/rcm-call-to-action-box/call-to-action-box-edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php',
            'canCache' => true
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
                'modules/rcm-three-choices/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-three-choices/rcm-three-choices-edit.js',
                ],
                'modules/rcm/plugins.css' => [
                    'modules/rcm-three-choices/style.css',
                ],
            ],
        ],
    ],
];