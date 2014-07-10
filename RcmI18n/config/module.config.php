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
            'RcmI18nTranslations' => array(
                'Translations' => array(
                    'resourceId' => 'Translations',
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
        )
    ),
    'translator_plugins' => array(
        'factories' => array(
            'RcmI18n\DbLoader' => 'RcmI18n\Factory\LoaderFactory',
        )
    ),
    'doctrine' => array(
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
                    'route' => '/rcmi18n/messages',
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
        ],
    ],
);