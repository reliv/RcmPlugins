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
 * @package   RcmPlugins\RcmNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(


    'rcmPlugin' => array(
        'RcmNavigation'=>array(
            'type' => 'Common',
            'display' => 'Navigation Menu',
            'tooltip' => 'Navigation menu that can display sub-menus when users mouse-over the main menu.',
            'icon' => '',
            'editJs'=>'/modules/rcm-navigation/edit.js',
            'defaultInstanceConfig'=>include __DIR__ . '/defaultInstanceConfig.php'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);