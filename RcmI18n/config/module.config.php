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

    'RcmUser' => array(
        'Acl\Config' => array(
            'ResourceProviders' => array(
                'RcmI18nTranslations' => array(
                    'translations' => array(
                        'resourceId' => 'translations',
                        'parentResourceId' => null,
                        'privileges' => array(
                            'read',
                            'update',
                            'create',
                            'delete',
                        ),
                        'name' => 'Translations',
                        'description' => 'Creating translations for other countries',
                    )
                )
            )
        )
    ),
    'navigation' => array(
        'RcmAdminMenu' => array(
            'Site' => array(
                'pages' => array(
                    'Translations' => array(
                        'label' => 'Translations',
                        'class' => 'RcmAdminMenu RcmBlankDialog Translations',
                        'uri' => '/modules/rcm-i18n/message-editor.html',
                        'title' => 'Translations',
                    )
                )
            ),
        )
    ),
    'translator' => array(

        'locale' => 'en_US',
        'remote_translation' => array(
            array(
                'type' => 'RcmI18n\DbLoader',
            ),
        ),
    ),
    /**
     * Can be removed after ZF2 PR
     */
    'service_manager' => array(
        'factories' => array(
            'MvcTranslator' => 'RcmI18n\Factory\TranslatorFactory',
            'RcmI18n\Model\Locales' => 'RcmI18n\Factory\LocalesFactory',
        )
    ),
    'translator_plugins' => array(
        'factories' => array(
            'RcmI18n\DbLoader' => 'RcmI18n\Factory\LoaderFactory',
        )
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'charset' => 'UTF8'
                ),
            )
        ),
        'driver' => array(
            'RcmI18n' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/RcmI18n/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmI18n' => 'RcmI18n'
                )
            )
            /**
             * NOTE: SOME KIND OF DOCTRINE UTF8 SETTING IS REQUIRED HERE OR
             * FRENCH CHARACTERS WILL NOT DISPLAY CORRECTLY IN BROWSERS
             */
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'RcmI18n\Controller\Locale' => 'RcmI18n\Controller\LocaleController',
            'RcmI18n\Controller\Messages' => 'RcmI18n\Controller\MessagesController'
        )
    ),
    'router' => array(
        'routes' => array(
            'locales' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcmi18n/locales',
                    'defaults' => array(
                        'controller' => 'RcmI18n\Controller\Locale',
                    ),
                ),
            ),
            'messages' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcmi18n/messages/:locale[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmI18n\Controller\Messages',
                    ),
                ),
            ),
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'translate' => 'RcmI18n\Factory\TranslateHtmlFactory',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-i18n/' => __DIR__ . '/../public/',
            ],
//            'collections' => array(
//                'modules/rcm-admin/js/rcm-admin.js' => array(
//                    'vendor/reliv/RcmPlugins/RcmI18n/public/rcmTranslationsCtrl.js',
//                    'vendor/reliv/RcmPlugins/RcmAngularJs/public/angular-ui/bootstrap/ui-bootstrap-0.11.0.min.js'
//                ),
//            ),
//            'paths' => array(
//                __DIR__ . '/../../..' , //. '/../public'
//            ),
        ],

    ],
);