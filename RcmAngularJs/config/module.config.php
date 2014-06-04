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
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-angular-js/' => __DIR__ . '/../public/',
            ),
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'rcmIncludeAngularJs' =>
                'RcmAngularJs\View\Helper\IncludeAngularJs',

            'rcmIncludeAngularJsUiBootstrap' =>
                'RcmAngularJs\View\Helper\IncludeAngularJsBootstrap',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);