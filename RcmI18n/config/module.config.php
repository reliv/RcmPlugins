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

    'RcmI18n' => [
        'defaultLocale' => 'en_US',
    ],
    'RcmUser' => [
        'Acl\Config' => [
            'ResourceProviders' => [
                'RcmI18nTranslations' => [
                    'translations' => [
                        'resourceId' => 'translations',
                        'parentResourceId' => null,
                        'privileges' => [
                            'read',
                            'update',
                            'create',
                            'delete',
                        ],
                        'name' => 'Translations',
                        'description' => 'Creating translations for other countries',
                    ]
                ]
            ]
        ]
    ],
    'navigation' => [
        'RcmAdminMenu' => [
            'Site' => [
                'pages' => [
                    'Translations' => [
                        'label' => 'Translations',
                        'class' => 'RcmAdminMenu RcmBlankDialog Translations',
                        'uri' => '/modules/rcm-i18n/message-editor.html',
                        'title' => 'Translations',
                    ]
                ]
            ],
        ]
    ],
    'translator' => [
        'locale' => 'en_US',
        'event_manager_enabled' => true,
        'remote_translation' => [
            [
                'type' => 'RcmI18n\DbLoader',
            ],
        ],
    ],
    /**
     * Can be removed after ZF2 PR
     */
    'service_manager' => [
        'factories' => [
            'MvcTranslator' => 'RcmI18n\Factory\TranslatorFactory',
            'RcmI18n\Model\Locales' => 'RcmI18n\Factory\LocalesFactory',
            'RcmI18n\Event\MissingTranslationListener' => 'RcmI18n\Factory\MissingTranslationListenerFactory',
        ]
    ],
    'translator_plugins' => [
        'factories' => [
            'RcmI18n\DbLoader' => 'RcmI18n\Factory\LoaderFactory',
        ]
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'charset' => 'UTF8'
                ],
            ]
        ],
        'driver' => [
            'RcmI18n' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'RcmI18n' => 'RcmI18n'
                ]
            ]
            /**
             * NOTE: SOME KIND OF DOCTRINE UTF8 SETTING IS REQUIRED HERE OR
             * FRENCH CHARACTERS WILL NOT DISPLAY CORRECTLY IN BROWSERS
             */
        ],
    ],
    'controllers' => [
        'invokables' => [
            'RcmI18n\Controller\Locale' => 'RcmI18n\Controller\LocaleController',
            'RcmI18n\Controller\Messages' => 'RcmI18n\Controller\MessagesController',
        ]
    ],
    'router' => [
        'routes' => [
            'locales' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcmi18n/locales',
                    'defaults' => [
                        'controller' => 'RcmI18n\Controller\Locale',
                    ],
                ],
            ],
            'messages' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcmi18n/messages/:locale[/:id]',
                    'defaults' => [
                        'controller' => 'RcmI18n\Controller\Messages',
                    ],
                ],
            ],
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'translate' => 'RcmI18n\Factory\TranslateHtmlFactory',
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-i18n/' => __DIR__ . '/../public/',
            ],
        ],

    ],
];