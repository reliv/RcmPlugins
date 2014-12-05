<?php

/**
 * ZF2 Module Config file for Rcm
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
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-angular-js/' => __DIR__ . '/../public/',
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'rcmIncludeAngularJsUiBootstrap' =>
                'RcmAngularJs\View\Helper\IncludeAngularJsBootstrap',
            'rcmIncludeAngularJsUiTinyMce' =>
                'RcmAngularJs\View\Helper\IncludeAngularJsTinyMce',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];