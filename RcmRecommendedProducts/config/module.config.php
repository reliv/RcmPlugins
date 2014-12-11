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
        'RcmRecommendedProducts' => [
            'type' => 'Content Templates',
            'display' => 'Recommended Products',
            'tooltip' => '',
            'icon' => '',
            'editJs' => '/modules/rcm-recommended-products/rcm-recommended-products-edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'

        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmRecommendedProducts' => 'RcmRecommendedProducts\Factory\PluginControllerFactory',
        ],
    ],
    'controllers' => [
        'factories' => [
            'RcmRecommendedProductsController'
            => 'RcmRecommendedProducts\Factory\RcmRecommendedDisplayControllerFactory',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'rcmRecommendedProductsList' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-recommended-list-refresh/[:productId]',
                    'defaults' => [
                        'controller'
                        => 'RcmRecommendedProductsController',
                        'action' => 'refreshProductList',
                    ],
                ],
            ],
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-recommended-products/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                // required for admin edit //
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-recommended-products/rcm-recommended-products-edit.js',
                ],
            ],
        ],

    ],

];