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
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
return [

    'navigation' => [
        'RcmAdminMenu' => [
            'Page' => [
                'label' => 'Page',
                'uri' => '#',
                'pages' => [
                    'New Page' => [
                        'label' => 'New Page',
                        'route' => 'RcmAdmin\Page\New',
                        'class' => 'RcmAdminMenu RcmFormDialog',
                        'title' => 'New Page',
                    ],
                    'Edit' => [
                        'label' => 'Edit',
                        'uri' => '#',
                        'pages' => [
                            'AddRemoveArrangePlugins' => [
                                'label' => 'Add/Remove/Arrange Plugins',
                                'class' => 'rcmAdminEditButton',
                                'uri' => "javascript:RcmAdminService.rcmAdminEditButtonAction('arrange');",
                            ],
                            'PageProperties' => [
                                'label' => 'Page Properties',
                                'class' => 'RcmAdminMenu RcmBlankDialog',
                                'uri' => '/modules/rcm-admin/page-properties.html',
                            ],
                            'PagePermissions' => [
                                'label' => 'Page Permissions',
                                'class' => 'RcmAdminMenu RcmBlankDialog',
                                'route' => 'RcmAdmin\Page\PagePermissions',
                                'params' => [
                                    'rcmPageName' => ':rcmPageName',
                                    'rcmPageType' => ':rcmPageType',
                                ],
                            ],
                        ]
                    ],
                    'Copy To' => [
                        'label' => 'Copy To...',
                        'uri' => '#',
                        'rcmOnly' => true,
                        'pages' => [
                            'Page' => [
                                'label' => 'Template',
                                'route' => 'RcmAdmin\Page\CreateTemplateFromPage',
                                'class' => 'RcmAdminMenu RcmFormDialog',
                                'title' => 'Copy To Template',
                                'params' => [
                                    'rcmPageName' => ':rcmPageName',
                                    'rcmPageType' => ':rcmPageType',
                                    'rcmPageRevision' => ':rcmPageRevision'
                                ],
                                'acl' => [
                                    'providerId' => 'Rcm\Acl\ResourceProvider',
                                    'resource' => 'sites.:siteId.pages.create'
                                ]
                            ],
                        ],
                    ],
                    'Drafts' => [
                        'label' => 'Drafts',
                        'uri' => '#',
                        'class' => 'drafts',
                        'rcmIncludeDrafts' => true,
                    ],
                    'Restore' => [
                        'label' => 'Restore',
                        'uri' => '#',
                        'class' => 'restore',
                        'rcmIncludePublishedRevisions' => true
                    ],
                ],
            ],
            'Site' => [
                'label' => 'Site',
                'uri' => '#',
                'pages' => [
                    'Manage Sites' => [
                        'label' => 'Manage Sites',
                        'class' => 'RcmAdminMenu rcmStandardDialog',
                        'uri' => '/modules/rcm-admin/view/manage-sites.html',
                        'title' => 'Manage Sites',
                    ],
                    'Create Site' => [
                        'label' => 'Create Site',
                        'class' => 'RcmAdminMenu rcmStandardDialog',
                        'uri' => '/modules/rcm-admin/view/create-site.html',
                        'title' => 'Create Site',
                    ],
                    'Copy Pages' => [
                        'label' => 'Copy Pages',
                        'class' => 'RcmAdminMenu rcmStandardDialog',
                        'uri' => '/modules/rcm-admin/view/site-page-copy.html',
                        'title' => 'Copy Pages',
                    ]
                ]
            ],
            'User' => [
                'label' => 'Users',
                'uri' => '#',
                'pages' => [
                    'RolesAndAccess' => [
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'Roles and Access',
                        'uri' => '/admin/rcmuser-acl',
                    ],
                    'UserManagement' => [
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'User Management',
                        'uri' => '/admin/rcmuser-users',
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'RcmAdmin\Page\New' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/rcm-admin/page/new',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'new',
                    ],
                ],
            ],
            'RcmAdmin\Page\CreateTemplateFromPage' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page/create-template-from-page/:rcmPageType/:rcmPageName[/[:rcmPageRevision]]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'createTemplateFromPage',
                    ],
                ],
            ],
            'RcmAdmin\Page\PublishPageRevision' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page/publish-page-revision/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'publishPageRevision',
                    ],
                ],
            ],
            'ApiAdminManageSitesController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/manage-sites[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminManageSitesController',
                    ]
                ],
            ],
            'ApiAdminLanguageController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/language[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminLanguageController',
                    ]
                ],
            ],
            'ApiAdminThemeController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/theme[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminThemeController',
                    ]
                ],
            ],
            'ApiAdminCountryController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/country[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminCountryController',
                    ]
                ],
            ],
            'ApiAdminSitePageController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/sites/:siteId/pages[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminSitePageController',
                    ]
                ],
            ],
            'ApiAdminPageTypesController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/pagetypes[/:id]',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\ApiAdminPageTypesController',
                    ]
                ],
            ],
            'RcmAdmin\Page\SavePage' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page/save-page/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'savePage',
                    ],
                ],
            ],
            'RcmAdmin\Page\PagePermissions' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page-permissions/:rcmPageType/:rcmPageName',
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PagePermissionsController',
                        'action' => 'pagePermissions',
                    ],
                ],
            ],
            'RcmAdmin\Page\GetPermissions' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/page/permissions/[:id]',
                    'constraints' => [
                        'id' => '[a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => 'RcmAdmin\Controller\PageViewPermissionsController',
                    ],
                ],
            ],
        ],
    ],
    'rcmAdmin' => [
        'createBlankPagesErrors' => [
            'missingItems'
            => 'Please make sure to include a Page Name and select the'
                . 'layout you wish to use.',
            'pageExists'
            => 'The page URL provided already exists'
        ],
        'saveAsTemplateErrors' => [
            'missingItems'
            => 'Please make sure to include a Page Name',
            'pageExists'
            => 'The page URL provided already exists',
            'revisionNotFound'
            => 'Unable to locate page revision.  '
                . 'Please contact the administrator.'
        ],
        'createSiteErrors' => [
            'missingItems'
            => 'Some needed information is missing.  '
                . 'Please check and make sure to include'
                . ' a domain, country, and language.',
            'countryNotFound'
            => 'Unable to locate country to save.  '
                . 'Please contact and administrator or try again.',
            'languageNotFound'
            => 'Unable to locate language to save.  '
                . 'Please contact and administrator or try again.',
            'domainInvalid'
            => 'Domain exists or is invalid.',
            'newSiteNotImplemented'
            => 'Creating a new blank site has not'
                . ' been implemented yet.',
            'siteNotFound'
            => 'Unable to locate the site to clone.  '
                . 'Please contact and administrator or try again.',
        ],
        'adminRichEditor' => 'tinyMce',
        'defaultSiteSettings' => [
            'siteLayout' => "GuestSitePage",
            'siteTitle' => "My Site",
            'language' => [
                'iso639_2t' => "eng"
            ],
            'country' => [
                'iso3' => "USA",
            ],
            'status' => "A",
            'favIcon' => "/images/favicon.ico",
            'loginPage' => "/login",
            'notAuthorizedPage' => "/not-authorized",
            'notFoundPage' => "/not-found",
            'containers' => [
                'guestTopNavigation',
                'guestMainNavigation',
                'guestRightColumn',
                'guestFooter',
            ],
            'pages' => [
                [
                    'name' => 'login',
                    'description' => 'Login Page.',
                    'pageTitle' => 'Login',
                    'plugins' => [
                        [
                            'plugin' => 'RcmLogin',
                            'displayName' => 'Login Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                        ],
                    ],
                ],
                [
                    'name' => 'not-authorized',
                    'description' => 'Not Authorized Page.',
                    'pageTitle' => 'Not Authorized',
                    'plugins' => [
                        [
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Login Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [
                                'html' => '<h1>Access Denied</h1>',
                            ]
                        ],
                    ],
                ],
                [
                    'name' => 'not-found',
                    'description' => 'Not Found Page.',
                    'pageTitle' => 'Not Found',
                    'plugins' => [
                        [
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Login Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [
                                'html' => '<h1>Page Not Found</h1>',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
    'includeFileManager' => [
        'files' => [
            'style.css' => [
                'destination' => __DIR__ . '/../../../../public/css',
                'header' => __DIR__ . '/../../../../public/css/styleHeader.css',
            ],
            'editStyle.css' => [
                'destination' => __DIR__ . '/../../../../public/css',
                'header' =>
                    __DIR__ . '/../../../../public/css/editStyleHeader.css',
            ],
            'script.js' => [
                'destination' => __DIR__ . '/../../../../public/js',
                'header' => __DIR__ . '/../../../../public/js/scriptHeader.js',
            ],
            'editScript.js' => [
                'destination' => __DIR__ . '/../../../../public/js',
                'header' =>
                    __DIR__ . '/../../../../public/js/editScriptHeader.js',
            ],
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-admin/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/js/rcm-admin.js' => [
                    'modules/rcm-admin/js/dialog/rcm-dialog.js',
                    'modules/rcm-admin/js/navigation/rcm-admin-menu.js',
                    'modules/rcm-admin/js/admin/rcm-admin.js',
                    'modules/rcm-admin/js/admin/rcm-edit.js',
                    'modules/rcm-admin/js/jquery/jquery-dialog-inputs.js',
                    'modules/rcm-admin/js/admin/ajax-plugin-edit-helper.js',
                    'modules/rcm-admin/js/admin/available-plugins-menu.js',
                    'modules/rcm-admin/js/admin/bootstrap-alert-confirm.js',
                    'modules/rcm-admin/js/admin/plugin-drag.js',
                    'modules/rcm-admin/js/admin/session.js',
                    'modules/rcm-admin/js/permissions/page-permissions.js',
                    'modules/rcm-angular-js/angular-multi-select/angular-multi-select.js',
                    'modules/rcm-admin/js/dialog/rcm-popout-window.js',
                    'modules/rcm-admin/js/admin/rcm-save-ajax-admin-window.js',
                    'modules/rcm-admin/js/manage-sites/controller.js',
                    'modules/rcm-admin/js/create-site/controller.js',
                    'modules/rcm-admin/js/site-page-copy/rcm-admin-site-page-copy.js',
                ],
                'modules/rcm-admin/css/rcm-admin.css' => [
                    'modules/rcm-admin/css/admin-jquery-ui.css',
                    'modules/rcm-admin/css/cm-admin.css',
                    'modules/rcm-admin/css/layout-editor.css',
                    'modules/rcm-admin/css/rcm-admin-panel.css',
                    'modules/rcm-admin/css/rcm-admin-navigation.css',
                    'modules/rcm-admin/css/permissions.css',
                    'modules/rcm-angular-js/angular-multi-select/angular-multi-select.css'
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmAdmin\EventListener\DispatchListener'
            => 'RcmAdmin\Factory\DispatchListenerFactory',
            'RcmAdmin\Controller\AdminPanelController'
            => 'RcmAdmin\Factory\AdminPanelControllerFactory',
            'RcmAdminNavigation'
            => 'RcmAdmin\Factory\AdminNavigationFactory',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formPageLayout' => 'RcmAdmin\View\Helper\FormPageLayout',
            'displayErrors' => 'RcmAdmin\View\Helper\DisplayErrors',
            'availablePluginsList' => 'RcmAdmin\View\Helper\AvailablePluginsJsList',
        ]
    ],
    'form_elements' => [
        'invokables' => [
            'mainLayout' => 'RcmAdmin\Form\Element\MainLayout',
        ],
        'factories' => [
            'RcmAdmin\Form\NewPageForm' => 'RcmAdmin\Factory\NewPageFormFactory',
            'RcmAdmin\Form\CreateTemplateFromPageForm'
            => 'RcmAdmin\Factory\CreateTemplateFromPageFormFactory',
        ],
    ],
    'controllers' => [
        'factories' => [
            'RcmAdmin\Controller\PageController'
            => 'RcmAdmin\Factory\PageControllerFactory',
        ],
        'invokables' => [
            'RcmAdmin\Controller\PagePermissionsController'
            => 'RcmAdmin\Controller\PagePermissionsController',
            'RcmAdmin\Controller\PageViewPermissionsController' =>
                'RcmAdmin\Controller\PageViewPermissionsController',
            'RcmAdmin\Controller\ApiAdminManageSitesController'
            => 'RcmAdmin\Controller\ApiAdminManageSitesController',
            'RcmAdmin\Controller\ApiAdminLanguageController'
            => 'RcmAdmin\Controller\ApiAdminLanguageController',
            'RcmAdmin\Controller\ApiAdminThemeController'
            => 'RcmAdmin\Controller\ApiAdminThemeController',
            'RcmAdmin\Controller\ApiAdminCountryController'
            => 'RcmAdmin\Controller\ApiAdminCountryController',
            'RcmAdmin\Controller\ApiAdminSitePageController'
            => 'RcmAdmin\Controller\ApiAdminSitePageController',
            'RcmAdmin\Controller\ApiAdminPageTypesController'
            => 'RcmAdmin\Controller\ApiAdminPageTypesController',

        ],
    ],
];
