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
        'RcmNavResponsive' => array(
            'type' => 'Common',
            'display' => 'Responsive Navigation',
            'tooltip' => 'Customizable hover-based navigation menu the converts to a drop-down box on smaller screens.',
            'icon' => '',
            'editJs' => '/modules/rcm-nav-responsive/edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'
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
                'modules/rcm-nav-responsive/' => __DIR__ . '/../public/',
            ),
            'collections' => array(
                'modules/rcm-admin/js/rcm-admin.js' => array(
                    'modules/rcm-nav-responsive/edit.js',
                ),
            ),
        ),
    ),

);