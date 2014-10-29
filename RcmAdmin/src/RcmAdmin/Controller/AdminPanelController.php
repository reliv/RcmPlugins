<?php
/**
 * Admin Panel Controller for the CMS
 *
 * This file contains the Admin Panel Controller for the CMS.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace RcmAdmin\Controller;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 * Admin Panel Controller for the CMS
 *
 * This is Admin Panel Controller for the CMS.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method boolean rcmIsSiteAdmin(Site $site)  Is Site Administrator
 */
class AdminPanelController extends AbstractActionController
{
    /** @var array */
    protected $adminPanelConfig;

    /** @var \RcmUser\Service\RcmUserService */
    protected $userService;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \Rcm\Acl\CmsPermissionChecks */
    protected $cmsPermissionChecks;

    /**
     * Constructor
     *
     * @param array               $adminPanelConfig    Admin Config
     * @param RcmUserService      $userService         RmcUser User Service
     * @param Site                $currentSite         Rcm Site Id
     * @param CmsPermissionChecks $cmsPermissionChecks Rcm Service for CMS permissions
     */
    public function __construct(
        Array          $adminPanelConfig,
        RcmUserService $userService,
        Site           $currentSite,
        CmsPermissionChecks $cmsPermissionChecks
    ) {
        $this->adminPanelConfig = $adminPanelConfig;
        $this->userService = $userService;
        $this->currentSite = $currentSite;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
    }

    /**
     * Get the Admin Menu Bar
     *
     * @return mixed
     */
    public function getAdminWrapperAction()
    {
        $allowed = $this->cmsPermissionChecks->siteAdminCheck($this->currentSite);

        if (!$allowed) {
            return null;
        }

        $view = new ViewModel();
        if($this->checkRestrictedPage() == true) {
            $view->setVariable('restrictions',true);
        }

        $view->setVariable('adminMenu', $this->adminPanelConfig);
        $view->setTemplate('rcm-admin/admin/admin');
        return $view;
    }

    public function checkRestrictedPage() {
        /** @var RouteMatch $routeMatch */
        $routeMatch = $this->getEvent()->getRouteMatch();

        $sourcePageName = $routeMatch->getParam('page', 'index');
        $pageType = $routeMatch->getParam('pageType', 'n');

        /** @todo Move resource to external modal */
        $resourceId = 'sites.' . $this->currentSite->getSiteId() . '.pages.' . $pageType . '.'
            . $sourcePageName;

        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );
        //getting all set rules by resource Id
        $rules = $aclDataService->getRulesByResource($resourceId)->getData();
        //getting list of all dynamically created roles
        $allRoles = $aclDataService->getAllRoles()->getData();
        //if all aal rolea rea selected than means no restrictions on page
        if(count($rules) > 0 && count($rules) != count($allRoles)) {
            $restrictions = true;
        } elseif(count($rules) == count($allRoles)) {
            $restrictions = false;
        }
        else {
            $restrictions = false;
        }

        return $restrictions;
    }

  }