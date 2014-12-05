<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * @category  Reliv
 * @package   RcmTinyMce
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright $2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
return [
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-tinymce-js/' => __DIR__ . '/../public/',
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'rcmIncludeTinyMceJs' =>
                'RcmTinyMce\View\Helper\IncludeTinyMce',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];