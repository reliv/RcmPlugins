<?php
/**
 * Service Factory for the Admin Navigation Container
 *
 * This file contains the factory needed to generate a Admin Navigation Container.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Admin Navigation Container
 *
 * Factory for the Admin Navigation Container
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class AdminNavigationFactory extends AbstractNavigationFactory
{
    /** @var  \RcmUser\Service\RcmUserService */
    protected $rcmUserService;

    /** @var \Rcm\Service\SiteManager */
    protected $siteManager;

    /** @var  \Rcm\Service\PageManager */
    protected $pageManager;

    /** @var  \Rcm\Entity\Page */
    protected $page = null;

    /** @var  \Rcm\Acl\CmsPermissionChecks */
    protected $cmsPermissionChecks;

    protected $pageRevision = null;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->rcmUserService = $serviceLocator->get('RcmUser\Service\RcmUserService');
        $this->cmsPermissionChecks = $serviceLocator->get('Rcm\Acl\CmsPermissionsChecks');
        $this->siteManager = $serviceLocator->get('Rcm\Service\SiteManager');

        $config = $serviceLocator->get('config');

        /** @var  \Rcm\Service\PageManager $pageManager */
        $this->pageManager = $this->siteManager->getPageManager();

        $application = $serviceLocator->get('Application');

        /** @var RouteMatch $routeMatch */
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();

        if (!in_array($routeMatch->getMatchedRouteName(), $config['Rcm']['RcmCmsPageRouteNames'])) {
            return parent::createService($serviceLocator);
        }

        $pageMatch = $routeMatch->getParam('page', 'index');
        $this->pageRevision = $routeMatch->getParam('revision', null);
        $pageTypeMatch = $routeMatch->getParam('pageType', 'n');

        if (!empty($pageMatch)) {
            $this->page = $this->pageManager->getRevisionInfo(
                $pageMatch,
                $this->pageRevision,
                $pageTypeMatch,
                $this->cmsPermissionChecks->shouldShowRevisions(
                    $this->siteManager->getCurrentSiteId(),
                    $pageTypeMatch,
                    $pageMatch
                )
            );
        }

        return parent::createService($serviceLocator);
    }


    /**
     * Get the name of the navigation container
     *
     * @return string
     */
    protected function getName()
    {
        $name = 'RcmAdminMenu';

        return $name;
    }

    /**
     * Zend Inject Components.
     *
     * @param array $pages
     * @param RouteMatch $routeMatch
     * @param Router $router
     * @param null|Request $request
     * @return mixed
     */
    protected function injectComponents(array $pages, RouteMatch $routeMatch = null, Router $router = null, $request = null)
    {
        foreach ($pages as $key => &$page) {

            if (!$this->shouldShowInNavigation($page)) {
                unset($pages[$key]);
                continue;
            }

            $this->setupRcmNavigation($page);

            $hasUri = isset($page['uri']);
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $routeMatch) {
                    $page['routeMatch'] = $routeMatch;
                }
                if (!isset($page['router'])) {
                    $page['router'] = $router;
                }
            } elseif ($hasUri) {
                if (!isset($page['request'])) {
                    $page['request'] = $request;
                }
            }



            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router, $request);
            }
        }

        return $pages;
    }

    /**
     * Should link be shown in nav bar?
     *
     * @param $page
     *
     * @return bool
     */
    protected function shouldShowInNavigation(&$page)
    {
        if (isset($page['rcmOnly'])
            && $page['rcmOnly']
            && empty($this->page)
        ) {
            return false;
        }

        if (isset($page['acl']) && is_array($page['acl']) && !empty($page['acl']['resource'])) {

            $providerId = null;
            if (!empty($page['acl']['providerId'])) {
                $providerId = $page['acl']['providerId'];
            }

            $privilege = null;
            if (!empty($page['acl']['privilege'])) {
                $privilege = $page['acl']['privilege'];
            }

            $resource = $page['acl']['resource'];

            $resource = str_replace(
                array(':siteId',':pageName'),
                array($this->siteManager->getCurrentSiteId(), $this->page['name']),
                $resource
            );

            if (!empty($this->page)) {
                $resource = str_replace(
                    array(':siteId',':pageName'),
                    array($this->siteManager->getCurrentSiteId(), $this->page['name']),
                    $resource
                );
            } else {
                $resource = str_replace(
                    array(':siteId'),
                    array($this->siteManager->getCurrentSiteId()),
                    $resource
                );
            }

            if (!$this->rcmUserService->isAllowed($resource, $privilege, $providerId)) {
                return false;
            }

        }

        return true;
    }

    /**
     * Setup Rcm Navigation
     *
     * @param $page
     */
    protected function setupRcmNavigation(&$page) {

        if (empty($this->page)) {
            return;
        }

        if (isset($page['params']) && is_array($page['params'])) {
            array_walk($page['params'], array($this, 'updatePlaceHolders'));
        }

        if (!empty($page['rcmIncludeDrafts'])) {
            $page['pages'] = $this->getRevisionList(false);
        }

        if (!empty($page['rcmIncludePublishedRevisions'])) {
            $page['pages'] = $this->getRevisionList(true);
        }

    }

    /**
     * Get Draft Revision List
     *
     * @return array
     */
    protected function getRevisionList($published=false)
    {
        $return = array();

        $drafts = $this->pageManager->getRevisionList(
            $this->page['name'],
            $this->page['pageType'],
            $published,
            10
        );

        if (empty($drafts)) {
            return $return;
        }

        /** @var \Rcm\Entity\Revision $draft */
        foreach ($drafts['revisions'] as $draft) {
            if ($this->page['revision']['revisionId'] == $draft['revisionId']) {
                continue;
            }

            $return[] = array(
                'label'  => $draft['createdDate']->format("r").' - '.$draft['author'],
                'route'  => 'contentManagerWithPageType',
                'class' => 'revision',
                'params' => array(
                    'page' => $this->page['name'],
                    'pageType' => $this->page['pageType'],
                    'revision' => $draft['revisionId']
                )
            );
        }

        return $return;
    }

    /**
     * Update config place holders with correct data prior to building the navigation
     *
     * @param $item
     */
    protected function updatePlaceHolders(&$item) {

        if (empty($this->page)) {
            return;
        }

        $find = array(
            ':rcmPageName',
            ':rcmPageType',
            ':rcmPageRevision'
        );

        $replace = array(
            $this->page['name'],
            $this->page['pageType'],
            $this->page['revision']['revisionId']
        );

        $item = str_replace($find, $replace, $item);
    }

}