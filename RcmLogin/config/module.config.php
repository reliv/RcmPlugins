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

return array(

    'rcmPlugin' => array(
        'RcmLogin' => array(
            'type' => 'Common',
            'display' => 'Login Area',
            'tooltip' => 'Adds login area to page',
            'icon' => '',
            'requireHttps' => true,
            'editJs' => '/modules/rcm-login/rcm-login-edit.js',
            'postLoginRedirectUrl' => '/login-home',
            'defaultInstanceConfig' => include __DIR__ .
                    '/defaultInstanceConfig.php',
            'canCache' => false,
            'uncategorizedErrorRedirect' => "/account-issue"
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-login/' => __DIR__ . '/../public/',
            ),
        ),
    ),

    'service_manager' => array(

        'factories' => array(
            'RcmLogin' => 'RcmLogin\Factory\PluginControllerFactory',
        )

    ),
);