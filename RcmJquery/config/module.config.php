<?php
/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-jquery/' => __DIR__ . '/../public/',
            ),
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'rcmIncludeJquery'        => 'RcmJquery\View\Helper\IncludeJquery',
            'rcmIncludeJqueryUi'      => 'RcmJquery\View\Helper\IncludeJqueryUi',
            'rcmIncludeJqueryBlockUi' => 'RcmJquery\View\Helper\IncludeJqueryUi',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);