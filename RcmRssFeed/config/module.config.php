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
        'RcmRssFeed'=>array(
            'type' => 'Basic',
            'display' => 'Rss Feed Reader',
            'tooltip' => 'Rss Reader and Display',
            'icon' => '',
            'editJs'=>'/modules/rcm-rss-feed/edit.js',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'RcmRssFeed\Controller\PluginController'
            => 'RcmRssFeed\Controller\PluginController',
        ),
    ),

    'router' => array(
        'routes' => array (
            'rcm-rss-proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rcm-rss-proxy',
                    'defaults' => array(
                        'controller'
                        => 'RcmRssFeed\Controller\PluginController',
                        'action'     => 'rssProxy',
                    ),
                ),
            ),
        ),
    ),
);