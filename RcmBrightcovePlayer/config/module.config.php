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
 * @package   RcmBrightcovePlayer
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

return [

    'rcmPlugin' => [
        'RcmBrightcovePlayer' => [
            'type'
            => 'Social Media',
            'display'
            => 'Brightcove Video Player',
            'tooltip'
            => 'Add a Brightcove Video to the page',
            'icon'
            => '',
            'urlToken'
            => 'W3IM0czQo2YQ1EIM5CSIMj2KYCX0DrK4_vhAYu9vGSiC5Fw0-cgvow..',
            'readToken'
            => 'FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
            'editJs'
            => '/modules/rcm-brightcove-player/rcm-brightcove-player-edit.js',
            'defaultInstanceConfig'
            => include __DIR__ . '/defaultInstanceConfig.php'
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-brightcove-player/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/plugins.js' => [
                    'modules/rcm-brightcove-player/rcm-brightcove-player-angular-module.js',
                    'modules/rcm-brightcove-player/rcm-brightcove-player-multi.js',
                    'modules/rcm-brightcove-player/rcm-brightcove-player-single.js'
                ],
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-brightcove-player/rcm-brightcove-player-edit.js',
                ],
            ],
        ],
    ],
    // @codingStandardsIgnoreStart
    'service_manager' => [
        'factories' => [
            'RcmBrightcovePlayer' => 'RcmBrightcovePlayer\Factory\RcmBrightcovePlayerControllerFactory',
        ],
    ],
    // @codingStandardsIgnoreEnd

];