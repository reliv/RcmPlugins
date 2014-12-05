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
    'service_manager' => [
        'factories' => [
            'RcmHtmlPurifier' => 'RcmHtmlPurifier\Factory\HtmlPurifierFactory',
        ]
    ],
    'controller_plugins' => [
        'invokables' => [
            'rcmHtmlPurify' =>
                'RcmHtmlPurifier\Controller\Plugin\HtmlPurify',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'rcmHtmlPurify' =>
                'RcmHtmlPurifier\View\Helper\HtmlPurify',
        ],
    ],
];