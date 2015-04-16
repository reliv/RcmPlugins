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

    'router' => array(
        'routes' => array(
            'issuuRest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/issuu/:username/:id',
                    'defaults' => array(
                        'controller' => 'RcmIssuu\Controller\DocumentListController',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],

        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-issuu/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-issuu/rcm-issuu-document.js',
                    'modules/rcm-issuu/rcm-issuu-api-processor.js',
                    'modules/rcm-issuu/rcm-issuu-edit-dialog-form.js',
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
            'RcmIssuu\Controller\DocumentListController' => '\RcmIssuu\Factory\DocumentListControllerFactory',
        ],
    ],
];
