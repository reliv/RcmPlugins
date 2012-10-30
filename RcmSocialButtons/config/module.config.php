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
 * @package   RcmPlugins\RcmSocialButtons
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

return array(

    'rcmPlugin' => array(
        'RcmSocialButtons'=>array(

            'type' => 'Basic',
            'display' => 'Social Share Buttons',
            'tooltip' => 'Facebook, Twitter, and many more social sharing buttons powered by "Share This"',
            'icon' => '',
            'editJs'=>'/modules/rcm-social-buttons/edit.js',

            //Plugin Specific
            'availableButtons' => array(
                'facebook' => 'Facebook',
                'google' => 'Google',
                'twitter' => 'Twitter',
                'pinterest' => 'Pinterest',
                'email' => 'Email',
                'sharethis' => 'Share This'
            )
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);