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
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-bright-cove-lib/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/plugins.js' => [
                    'modules/rcm-bright-cove-lib/rcm-bright-cove-event-manager.js',
                    'modules/rcm-bright-cove-lib/rcm-brightcove-player-service.js',
                    'modules/rcm-bright-cove-lib/rcm-brightcove-api-service.js',
                    'modules/rcm-bright-cove-lib/keep-aspect-ratio.js'
                ],
            ],
        ],
    ],
];