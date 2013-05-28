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
 * @package   RcmPlugins\Navigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

return array(

    'rcmPlugin' => array(
        'RcmLoginOpenSourceVersionTempRename'=>array(
            'type' => 'Common',
            'display' => 'Login Area',
            'tooltip' => 'Adds login area to page',
            'icon' => '',
            'editJs'=>'/modules/rcm-login/rcm-login-edit.js',
            'postLoginRedirectUrl'=>'/processLogin',
            'newInstanceConfig'=>include __DIR__ . '/newInstanceConfig.php'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'router' => array(
        'routes' => array (
//   THIS SHOULD BE USED TO FORCE SSL BUT THERE SEEMS TO BE A BUG IN ZF2 SCHEME ROUTING
//            'contentManagerLogin' => array(
//                'type' => 'Scheme',
//                'options' => array(
//                    'route' => '/login/auth',
//                    'scheme' => 'https',
//                    'defaults' => array(
//                        'controller' => 'rcmLoginController',
//                        'action' => 'loginAuth',
//                    ),
//                ),
//            ),
//          TODO SWITCH TO THE ABOVE CONFIG
            'contentManagerLogin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/login/auth',
                    'defaults' => array(
                        'controller' => 'rcmLoginController',
                        'action'     => 'loginAuth',
                    ),
                ),
            ),
        ),
    ),
);