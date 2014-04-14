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
 * @license   http://www.nolicense.com None
 * @version   GIT: <git_id>
 */

return array(

    'rcmPlugin' => array(
        'RcmImageWithThumbnails' => array(
            'type' => 'Images & Slide Shows',
            'display' => 'Image Gallery',
            'tooltip' => 'Image with Thumbnails',
            'icon' => '',
            'editJs' => '/modules/rcm-image-with-thumbnails/edit.js',
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
                'modules/rcm-image-with-thumbnails/' => __DIR__ . '/../public/',
            ),
        ),
    ),

);