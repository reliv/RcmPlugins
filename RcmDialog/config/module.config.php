<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 */
return [

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-dialog/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-dialog/dialog.js' => [
                    'modules/rcm-dialog/rcm-dialog.js',
                    'modules/rcm-dialog/strategy/rcm-blank-dialog.js',
                    'modules/rcm-dialog/strategy/rcm-blank-iframe-dialog.js',
                    'modules/rcm-dialog/strategy/rcm-form-dialog.js',
                    'modules/rcm-dialog/strategy/rcm-standard-dialog.js',
                ],
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'rcmDialogIncludeRcmDialog' =>
                'RcmDialog\View\Helper\IncludeRcmDialog',
        ]
    ],

];
