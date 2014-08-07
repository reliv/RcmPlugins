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
                    'New' => array(
                        'label' => 'New',
                        'uri' => '#',
                        'pages' => array(
                            'Page' => array(
                                'label' => 'Page',
                                'route' => 'RcmAdmin\Page\New',
                                'class' => 'RcmAdminMenu RcmFormDialog',
                                'title' => 'New Page',
                            ),
                        ),
                    ),
                    'Edit' => array(
                        'label' => 'Edit',
                        'uri' => '#',
                        'pages' => array(
                            'PageProperties' => array(
                                'label' => 'Page Properties',
                                'class' => 'RcmAdminMenu RcmBlankDialog',
                                'uri' => '/modules/rcm-admin/page-properties.html',
                            ),
                        ),
                    ),
                ),
            ),
            'Site' => array(
                'label' => 'Site',
                'uri' => '#',
            ),
            'User' => array(
                'label' => 'Users',
                'uri' => '#',
                'pages' => array(
                    'RolesAndAccess' => array(
                        'label' => 'Roles and Access',
                        'uri' => '/admin/rcmuser-acl',
                    ),
                    'UserManagement' => array(
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
                        'controller' => 'RcmAdmin\Controller\NewPageController',
                        'action' => 'new',
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
        'adminRichEditor' => 'ckEditor',
//        'adminRichEditor' => 'tinyMce',
        //'adminRichEditor' => 'aloha',

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
                    'js/dialog/rcm-dialog.js',
                    'js/navigation/rcm-admin-menu.js',
                    'js/admin/rcm-admin.js',
                ),
            ),
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'RcmAdmin\EventListener\DispatchListener'
            => 'RcmAdmin\Factory\DispatchListenerFactory',
            'RcmAdmin\Controller\AdminPanelController'
            => 'RcmAdmin\Factory\AdminPanelControllerFactory',
            'RcmAdminNavigation' => 'RcmAdmin\Factory\AdminNavigationFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formPageLayout' => 'RcmAdmin\View\Helper\FormPageLayout',
            'displayErrors' => 'RcmAdmin\View\Helper\DisplayErrors',
        )
    ),
    'form_elements' => array(
        'invokables' => array(
            'mainLayout' => 'RcmAdmin\Form\Element\MainLayout',
        ),
        'factories' => array(
            'RcmAdmin\Form\NewPageForm' => 'RcmAdmin\Factory\NewPageFormFactory',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'RcmAdmin\Controller\NewPageController'
            => 'RcmAdmin\Factory\NewPageControllerFactory',
        )
    )
);