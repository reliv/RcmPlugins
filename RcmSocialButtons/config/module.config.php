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
        'RcmSocialButtons' => [

            'type' => 'Social Media',
            'display' => 'Share-it Social Buttons',
            'tooltip' => 'Facebook, Twitter, and many more social sharing buttons powered by "Share This"',
            'icon' => '',
            'editJs' => '/modules/rcm-social-buttons/edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php',
            //Plugin Specific
            'availableButtons' => [
                'facebook' => 'Facebook',
                'google' => 'Google',
                'twitter' => 'Twitter',
                'pinterest' => 'Pinterest',
                'email' => 'Email',
                'sharethis' => 'Share This'
            ]
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
                'modules/rcm-social-buttons/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-social-buttons/edit.js',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmSocialButtons' => 'RcmSocialButtons\Factory\PluginControllerFactory'
        ]
    ]
];