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
        'RcmCallToActionBox' => [
            'type' => 'Content Templates',
            'display' => 'Call to Action Box',
            'tooltip' => 'Editable box with an image, headline, and description',
            'icon' => '',
            'editJs' => '/modules/rcm-call-to-action-box/call-to-action-box-edit.js',
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
                'modules/rcm-call-to-action-box/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                // required for admin edit //
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-call-to-action-box/call-to-action-box-edit.js',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmCallToActionBox' => 'RcmCallToActionBox\Factory\BaseControllerFactory'
        ]
    ]

];