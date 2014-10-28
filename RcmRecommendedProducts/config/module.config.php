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

return array(

    'rcmPlugin' => array(
        'RcmRecommendedProducts' => array(
            'type' => 'Content Templates',
            'display' => 'Recommended Products',
            'tooltip' => '',
            'icon' => '',
            'editJs' => '/modules/rcm-recommended-products/rcm-recommended-products-edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'

        ),
    ),
    'controllers' => array(
        'factories' => array(
            'RcmRecommendedProducts'
            => 'RcmRecommendedProducts\Factory\PluginControllerFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'RcmRecommendedProductsDisplay'
            =>'RcmRecommendedProducts\Factory\RcmRecommendedProductsDisplayControllerFactory',
        )

    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'rcmRecommendedProductsList' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-recommended-list-refresh/[:productId]',
                    'defaults' => array(
                        'controller'
                        => 'RcmRecommendedProductsDisplayController',
                        'action' => 'refreshProductList',
                    ),
                ),
            ),
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-recommended-products/' => __DIR__ . '/../public/',
            ),
            'collections' => array(
                // required for admin edit //
                'modules/rcm-admin/js/rcm-admin.js' => array(
                    'modules/rcm-recommended-products/rcm-recommended-products-edit.js',
                ),
            ),
        ),

    ),

);