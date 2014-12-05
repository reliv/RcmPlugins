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

return [

    'rcmPlugin' => [
        'RcmRssFeed' => [
            'type' => 'Social Media',
            'display' => 'Rss Feed Reader',
            'tooltip' => 'Rss Reader and Display',
            'icon' => '',
            'editJs' => '/modules/rcm-rss-feed/edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php',
            'cacheable' => true,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'rcm-rss-proxy' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-rss-proxy',
                    'defaults' => [
                        'controller' => 'RcmRssFeed\Controller\ProxyController',
                        'action' => 'rssProxy',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmRssFeed\Cache' => '\RcmRssFeed\Factory\RssCacheFactory'
        ]
    ],

    'controllers' => [
        'factories' => [
            'RcmRssFeed\Controller\ProxyController'
                => '\RcmRssFeed\Factory\ProxyControllerFactory',
            'RcmRssFeed' => '\RcmRssFeed\Factory\PluginControllerFactory',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-rss-feed/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-rss-feed/edit.js',
                ],
            ],
        ],
    ],
];