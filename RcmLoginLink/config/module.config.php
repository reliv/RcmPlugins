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
 * @package   RcmPlugins\RcmLoginLink
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

return array(

    'rcmPlugin' => array(
        'RcmLoginLink'=>array(
            'type' => 'Reliv',
            'display' => 'Login Link',
            'tooltip' => 'Login Link',
            'icon' => '',
            'editJs'=>'/modules/rcm-login-link/rcm-login-link-edit.js',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);