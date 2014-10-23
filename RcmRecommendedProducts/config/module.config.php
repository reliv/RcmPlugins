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
            'tooltip' => 'Recommended products with responsive design',
            'icon' => '',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-recommended-products/' => __DIR__ . '/../public/',
            ),
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
            'RcmRecommendedProducts'
            =>'RcmRecommendedProducts\Factory\BaseControllerFactory',
        )

    )
);