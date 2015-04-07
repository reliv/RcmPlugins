<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 */
return [

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-html-editor/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-html-editor/rcm-html-editor.js' => [

                    'modules/rcm-html-editor/adapter-tinymce/rcm-html-editor-config.js',
                    'modules/rcm-html-editor/adapter-tinymce/rcm-html-editor-options.js',
                    'modules/rcm-html-editor/adapter-tinymce/rcm-html-editor.js',
                    'modules/rcm-html-editor/adapter-tinymce/rcm-html-editor-toolbar.js',

                    'modules/rcm-html-editor/rcm-html-editor-guid.js',
                    'modules/rcm-html-editor/rcm-html-editor-event-manager.js',
                    'modules/rcm-html-editor/rcm-html-editor-service.js',

                    'modules/rcm-html-editor/angular-rcm-html-editor.js',
                ],
                'modules/rcm-html-editor/rcm-html-editor.css' => [
                    'modules/rcm-html-editor/adapter-tinymce/css/editor.css',
                ],
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'rcmHtmlEditorIncludeHtmlEditor' =>
                'RcmHtmlEditor\View\Helper\IncludeHtmlEditor',
        ]
    ],

];
