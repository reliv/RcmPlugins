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
 */

return array(

    'rcmPlugin' => array(
        'RcmBrightcovePlayer' => array(
            'type' => 'Social Media',
            'display' => 'Brightcove Video Player',
            'tooltip' => 'Add a Brightcove Video to the page',
            'icon' => '',
            'urlToken' => 'W3IM0czQo2YQ1EIM5CSIMj2KYCX0DrK4_vhAYu9vGSiC5Fw0-cgvow..',
            'readToken' => 'FqwdHcQgmq_r9A-CmzbuUqhy4cRl_9GtrGSlgiYwDraMpQfAE_EJ_Q..',
            'editJs' => '/modules/rcm-brightcove-player/rcm-brightcove-player-edit.js',
            'defaultInstanceConfig' => include
                    __DIR__ . '/defaultInstanceConfig.php'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);