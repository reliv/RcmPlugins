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
 * @package   RcmPlugins\RcmRotatingImage
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

return array(

    'rcmPlugin' => array(
        'RcmRotatingImage'=>array(
            'type' => 'Images & Slide Shows',
            'display' => 'Rotating Image',
            'tooltip' => 'Displays a different randomly selected image from a list each time a visitor comes to the site.',
            'icon' => '',
            'editJs'=>'/modules/rcm-rotating-image/edit.js',
            'editCss'=>'/modules/rcm-rotating-image/edit.css',
            'defaultInstanceConfig'=>include __DIR__ . '/defaultInstanceConfig.php'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);