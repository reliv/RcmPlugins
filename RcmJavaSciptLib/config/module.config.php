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
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-js-lib/' => __DIR__ . '/../public/',
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'rcmJsLibIncludeHtmlEditor' =>
                'RcmJavaSciptLib\View\Helper\IncludeHtmlEditor',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    /* <TEST> - TESTING ONLY */
    'controllers' => array(
        'invokables' => array(
            'RcmJavaSciptLib\Controller\TestController' =>
                'RcmJavaSciptLib\Controller\TestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'htmlEditorTest' => array(
                'may_terminate' => true,
                'type' => 'segment',
                'options' => array(
                    'route' => '/rcm-java-scipt-lib/:template',
                    'constraints' => array(
                        'template' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'RcmJavaSciptLib\Controller\TestController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    /* </TEST> */
);