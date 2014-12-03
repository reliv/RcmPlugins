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
return array(

    'navigation' => array(
        'RcmAdminMenu' => array(
            'Page' => array(
                'label' => 'Page',
                'uri' => '#',
                'pages' => array(
                    'New Page' => array(
                        'label' => 'New Page',
                        'route' => 'RcmAdmin\Page\New',
                        'class' => 'RcmAdminMenu RcmFormDialog',
                        'title' => 'New Page',
                    ),
                    'Edit' => array(
                        'label' => 'Edit',
                        'uri' => '#',
                        'pages' => array(
                            'AddRemoveArrangePlugins' => array(
                                'label' => 'Add/Remove/Arrange Plugins',
                                'class' => 'rcmAdminEditButton',
                                'uri' => "javascript:RcmAdminService.rcmAdminEditButtonAction('arrange');",
                            ),
                            'PageProperties' => array(
                                'label' => 'Page Properties',
                                'class' => 'RcmAdminMenu RcmBlankDialog',
                                'uri' => '/modules/rcm-admin/page-properties.html',
                            ),
                            'PagePermissions' => array(
                                'label' => 'Page Permissions',
                                'class' => 'RcmAdminMenu RcmBlankDialog',
                                'route' => 'RcmAdmin\Page\PagePermissions',
                                'params' => array(
                                    'rcmPageName' => ':rcmPageName',
                                    'rcmPageType' => ':rcmPageType',
                                ),
                            ),
                        )
                    ),
                    'Copy To' => array(
                        'label' => 'Copy To...',
                        'uri' => '#',
                        'rcmOnly' => true,
                        'pages' => array(
                            'Page' => array(
                                'label' => 'Template',
                                'route' => 'RcmAdmin\Page\CreateTemplateFromPage',
                                'class' => 'RcmAdminMenu RcmFormDialog',
                                'title' => 'Copy To Template',
                                'params' => array(
                                    'rcmPageName' => ':rcmPageName',
                                    'rcmPageType' => ':rcmPageType',
                                    'rcmPageRevision' => ':rcmPageRevision'
                                ),
                                'acl' => array(
                                    'providerId' => 'Rcm\Acl\ResourceProvider',
                                    'resource' => 'sites.:siteId.pages.create'
                                )
                            ),
                        ),
                    ),
                    'Drafts' => array(
                        'label' => 'Drafts',
                        'uri' => '#',
                        'class' => 'drafts',
                        'rcmIncludeDrafts' => true,
                    ),
                    'Restore' => array(
                        'label' => 'Restore',
                        'uri' => '#',
                        'class' => 'restore',
                        'rcmIncludePublishedRevisions' => true
                    ),
                ),
            ),
            'Site' => array(
                'label' => 'Site',
                'uri' => '#',
                'pages' => array(
                    'Manage Sites' => array(
                        'label' => 'Manage Sites',
                        'class' => 'RcmAdminMenu rcmStandardDialog',
                        'uri' => '/modules/rcm-admin/view/manage-sites.html',
                        'title' => 'Manage Sites',
                    ),
                    'Create Site' => array(
                        'label' => 'Create Site',
                        'class' => 'RcmAdminMenu rcmStandardDialog',
                        'uri' => '/modules/rcm-admin/view/create-site.html',
                        'title' => 'Create Site',
                    ),
//                    'Copy Pages' => array(
//                        'label' => 'Copy Pages',
//                        'class' => 'RcmAdminMenu rcmStandardDialog',
//                        'uri' => '/modules/rcm-admin/view/site-page-copy.html',
//                        'title' => 'Copy Pages',
//                    )
                )
            ),
            'User' => array(
                'label' => 'Users',
                'uri' => '#',
                'pages' => array(
                    'RolesAndAccess' => array(
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'Roles and Access',
                        'uri' => '/admin/rcmuser-acl',
                    ),
                    'UserManagement' => array(
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'User Management',
                        'uri' => '/admin/rcmuser-users',
                    ),
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'RcmAdmin\Page\New' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/rcm-admin/page/new',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'new',
                    ),
                ),
            ),
            'RcmAdmin\Page\CreateTemplateFromPage' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin/page/create-template-from-page/:rcmPageType/:rcmPageName[/[:rcmPageRevision]]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'createTemplateFromPage',
                    ),
                ),
            ),
            'RcmAdmin\Page\PublishPageRevision' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin/page/publish-page-revision/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'publishPageRevision',
                    ),
                ),
            ),
            'ApiAdminManageSitesController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/manage-sites[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminManageSitesController',
                    )
                ),
            ),
            'ApiAdminLanguageController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/language[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminLanguageController',
                    )
                ),
            ),
            'ApiAdminThemeController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/theme[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminThemeController',
                    )
                ),
            ),
            'ApiAdminCountryController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/country[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminCountryController',
                    )
                ),
            ),
            'ApiAdminSitePageController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/sites/:siteId/pages[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminSitePageController',
                    )
                ),
            ),
            'ApiAdminPageTypesController' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/pagetypes[/:id]',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\ApiAdminPageTypesController',
                    )
                ),
            ),
            'RcmAdmin\Page\SavePage' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin/page/save-page/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageController',
                        'action' => 'savePage',
                    ),
                ),
            ),
            'RcmAdmin\Page\PagePermissions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin/page-permissions/:rcmPageType/:rcmPageName',
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PagePermissionsController',
                        'action' => 'pagePermissions',
                    ),
                ),
            ),
            'RcmAdmin\Page\GetPermissions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/page/permissions/[:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'RcmAdmin\Controller\PageViewPermissionsController',
                    ),
                ),
            ),
        ),
    ),
    'rcmAdmin' => array(
        'createBlankPagesErrors' => array(
            'missingItems'
            => 'Please make sure to include a Page Name and select the'
                . 'layout you wish to use.',
            'pageExists'
            => 'The page URL provided already exists'
        ),
        'saveAsTemplateErrors' => array(
            'missingItems'
            => 'Please make sure to include a Page Name',
            'pageExists'
            => 'The page URL provided already exists',
            'revisionNotFound'
            => 'Unable to locate page revision.  '
                . 'Please contact the administrator.'
        ),
        'createSiteErrors' => array(
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
        ),
        'adminRichEditor' => 'tinyMce',
        'defaultSiteSettings' => array(
            'siteLayout' => "GuestSitePage",
            'siteTitle' => "My Site",
            'language' => array(
                'iso639_2t' => "eng"
            ),
            'country' => array(
                'iso3' => "USA",
            ),
            'status' => "A",
            'favIcon' => "/images/favicon.ico",
            'loginPage' => "/login",
            'notAuthorizedPage' => "/not-authorized",
            'notFoundPage' => "/not-found",
            'containers' => array(
                'guestTopNavigation',
                'guestMainNavigation',
                'guestRightColumn',
                'guestFooter',
            ),
            'pages' => array(
                array(
                    'name' => 'login',
                    'description' => 'Login Page.',
                    'pageTitle' => 'Login',
                    'plugins' => array(
                        array(
                            'plugin' => 'RcmLogin',
                            'displayName' => 'Login Area',
                            'instanceConfig' => array(),
                            'layoutContainer' => '4',
                        ),
                    ),
                ),
                array(
                    'name' => 'not-authorized',
                    'description' => 'Not Authorized Page.',
                    'pageTitle' => 'Not Authorized',
                    'plugins' => array(
                        array(
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Login Area',
                            'instanceConfig' => array(),
                            'layoutContainer' => '4',
                            'saveData' => array(
                                'html' => '<h1>Access Denied</h1>',
                            )
                        ),
                    ),
                ),
                array(
                    'name' => 'not-found',
                    'description' => 'Not Found Page.',
                    'pageTitle' => 'Not Found',
                    'plugins' => array(
                        array(
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Login Area',
                            'instanceConfig' => array(),
                            'layoutContainer' => '4',
                            'saveData' => array(
                                'html' => '<h1>Page Not Found</h1>',
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),
    'includeFileManager' => array(
        'files' => array(
            'style.css' => array(
                'destination' => __DIR__ . '/../../../../public/css',
                'header' => __DIR__ . '/../../../../public/css/styleHeader.css',
            ),
            'editStyle.css' => array(
                'destination' => __DIR__ . '/../../../../public/css',
                'header' =>
                    __DIR__ . '/../../../../public/css/editStyleHeader.css',
            ),
            'script.js' => array(
                'destination' => __DIR__ . '/../../../../public/js',
                'header' => __DIR__ . '/../../../../public/js/scriptHeader.js',
            ),
            'editScript.js' => array(
                'destination' => __DIR__ . '/../../../../public/js',
                'header' =>
                    __DIR__ . '/../../../../public/js/editScriptHeader.js',
            ),
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'modules/rcm-admin/' => __DIR__ . '/../public/',
            ),
            'collections' => array(
                'modules/rcm-admin/js/rcm-admin.js' => array(
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
                ),
                'modules/rcm-admin/css/rcm-admin.css' => array(
                    'modules/rcm-admin/css/admin-jquery-ui.css',
                    'modules/rcm-admin/css/cm-admin.css',
                    'modules/rcm-admin/css/layout-editor.css',
                    'modules/rcm-admin/css/rcm-admin-panel.css',
                    'modules/rcm-admin/css/rcm-admin-navigation.css',
                    'modules/rcm-admin/css/permissions.css',
                    'modules/rcm-angular-js/angular-multi-select/angular-multi-select.css'
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'RcmAdmin\EventListener\DispatchListener'
            => 'RcmAdmin\Factory\DispatchListenerFactory',
            'RcmAdmin\Controller\AdminPanelController'
            => 'RcmAdmin\Factory\AdminPanelControllerFactory',
            'RcmAdminNavigation'
            => 'RcmAdmin\Factory\AdminNavigationFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formPageLayout' => 'RcmAdmin\View\Helper\FormPageLayout',
            'displayErrors' => 'RcmAdmin\View\Helper\DisplayErrors',
            'availablePluginsList' => 'RcmAdmin\View\Helper\AvailablePluginsJsList',
        )
    ),
    'form_elements' => array(
        'invokables' => array(
            'mainLayout' => 'RcmAdmin\Form\Element\MainLayout',
        ),
        'factories' => array(
            'RcmAdmin\Form\NewPageForm' => 'RcmAdmin\Factory\NewPageFormFactory',
            'RcmAdmin\Form\CreateTemplateFromPageForm'
            => 'RcmAdmin\Factory\CreateTemplateFromPageFormFactory',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'RcmAdmin\Controller\PageController'
            => 'RcmAdmin\Factory\PageControllerFactory',
        ),
        'invokables' => array(
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

        ),
    ),
);
