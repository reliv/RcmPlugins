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
                                'class' => 'RcmAdminMenu RcmForm'
                            ),
                        ),
                    ),
                ),
            ),

            'Site' => array(
                'label' => 'Site',
                'uri' => '#',
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
        ),
    ),

    'rcmAdmin' => array(

        'createBlankPagesErrors' => array(
            'missingItems'
                => 'Please make sure to include a Page Name and select the'
                    .'layout you wish to use.',

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
                    .'Please contact the administrator.'
        ),

        'createSiteErrors' => array(
            'missingItems'
                => 'Some needed information is missing.  '
                    .'Please check and make sure to include'
                    .' a domain, country, and language.',

            'countryNotFound'
                => 'Unable to locate country to save.  '
                    .'Please contact and administrator or try again.',

            'languageNotFound'
                => 'Unable to locate language to save.  '
                    .'Please contact and administrator or try again.',

            'domainInvalid'
                => 'Domain exists or is invalid.',

            'newSiteNotImplemented'
                => 'Creating a new blank site has not'
                    .' been implemented yet.',

            'siteNotFound'
                => 'Unable to locate the site to clone.  '
                    .'Please contact and administrator or try again.',
        ),

        'adminRichEditor' => 'ckEditor',
//        'adminRichEditor' => 'tinyMce',
        //'adminRichEditor' => 'aloha',

        'adminPanel' => array(
            'Page' => array(
                'display' => 'Page',
                'aclGroups' => 'admin',
                'cssClass' => '',
                'href' => '#',
                'links' => array(
                    'New' => array(
                        'display' => 'New',
                        'aclGroups' => 'admin',
                        'cssClass' => 'newPageIcon',
                        'href' => '#',
                        'links' => array(
                            'Page' => array(
                                'display' => 'Page',
                                'aclResource' => 'admin',
                                'aclPermissions' => 'page.new',
                                'cssClass' => 'rcmNewPageIcon rcmNewPage',
                                'href' => '#',
                            ),
                        )
                    ),


                    'Edit' => array(
                        'display' => 'Edit',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array(
                            'Page' => array(
                                'display' => 'Edit Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditPageIcon rcmEditPage',
                                'href' => '#',
                            ),
                            'Page Layout' => array(
                                'display' => 'Add/Remove Plugins on Page',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmLayoutIcon rcmShowLayoutEditor',
                                'href' => '#',
                            ),

                            'Page Properties' => array(
                                'display' => 'Page Properties',
                                'aclGroups' => 'admin',
                                'cssClass' => 'PagePropertiesIcon rcmPageProperties',
                                'href' => '#',
                            ),
                        ),
                    ),

                    'Publish' => array(
                        'display' => 'Publish',
                        'aclGroups' => 'admin',
                        'cssClass' => 'publishIcon',
                        'href' => '#',
                        'links' => array(
                            'Stage' => array(
                                'display' => 'Stage (Only Admins Will See)',
                                'aclGroups' => 'admin',
                                'cssClass' => 'stageIcon',
                                'href' => '#',
                            ),

                            'Publish Now' => array(
                                'display' => 'Publish Now',
                                'aclGroups' => 'admin',
                                'cssClass' => 'publishIcon',
                                'href' => '#',
                            ),
                        ),
                    ),

                    'Copy To...' => array(
                        'display' => 'Copy To...',
                        'aclGroups' => 'admin',
                        'cssClass' => 'copyToIcon',
                        'href' => '#',
                        'links' => array(
                            'Template' => array(
                                'display' => 'Template',
                                'aclGroups' => 'admin',
                                'cssClass' => 'saveAsTemplate',
                                'href' => "#",
                                'onclick' => "rcmEdit.adminPopoutWindow("
                                    ."'/rcm-admin-get-save-as-template', "
                                    ."150, "
                                    ."430, "
                                    ."'Copy to Template'); "
                                    ."return false;"
                            ),
                        ),
                    ),

                    'Drafts' => array(
                        'display' => 'Drafts',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array()
                    ),

                    'Restore' => array(
                        'display' => 'Restore',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array()
                    ),

                ),
            ),

            'Site' => array(
                'display' => 'Site',
                'aclGroups' => 'admin',
                'cssClass' => 'draftsIcon',
                'href' => '#',
                'links' => array(
                    'New Site' => array(
                        'display' => 'New Site',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'onclick' => "rcmEdit.adminPopoutWindow("
                            ."'/rcm-admin-create-site', "
                            ."430, "
                            ."740, "
                            ."'Add New Site'); "
                            ."return false;"
                    ),

                    'Site-Wide Plugins' => array(
                        'display' => 'Site-Wide Plugins',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array(
                            'Edit Only Site-Wide Plugins' => array(
                                'display' => 'Edit Site-Wide Plugin Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditSiteWideIcon rcmEditSiteWide',
                                'href' => '#',
                            ),
                        ),
                    ),

                    'Site Properties' => array(
                        'display' => 'Site Properties',
                        'aclGroups' => 'admin',
                        'cssClass' => 'rcmEditSiteWideIcon rcmEditSiteWide',
                        'href' => '#',
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
        ),
    ),

    'service_manager' => array(
        'factories' => array (
            'RcmAdmin\EventListener\DispatchListener'
                => 'RcmAdmin\Factory\DispatchListenerFactory',

            'RcmAdmin\Controller\AdminPanelController'
                => 'RcmAdmin\Factory\AdminPanelControllerFactory',

            'RcmAdminNavigation' => 'RcmAdmin\Factory\AdminNavigationFactory'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'factories' => array(
            'RcmAdmin\Controller\PageController'
                => 'RcmAdmin\Factory\PageControllerFactory',
        )
    )
);