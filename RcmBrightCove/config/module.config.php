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
 * @package   RcmPlugins\RcmHtmlArea
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

return array(

    'rcmPlugin' => array(
        'RcmBrightCove'=>array(
            'type' => 'Social Media',
            'display' => 'Brightcove Video',
            'tooltip' => 'Add a Brightcove Video to the page',
            'icon' => '',
            'editJs'=>'/modules/rcm-bright-cove/js/edit.js',
            'newInstanceConfig'=>include __DIR__ . '/newInstanceConfig.php'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);