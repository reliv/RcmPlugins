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
 */
class AdminPanelController extends AbstractActionController
{
    /** @var array */
    protected $adminPanelConfig;

    /** @var \RcmUser\Service\RcmUserService */
    protected $userService;

    /** @var integer */
    protected $siteId;

    /**
     * Constructor
     *
     * @param array          $adminPanelConfig Admin Config
     * @param RcmUserService $userService      RmcUser User Service
     * @param integer        $siteId           Rcm Site Id
     */
    public function __construct(
        Array          $adminPanelConfig,
        RcmUserService $userService,
        $siteId
    ) {
        $this->adminPanelConfig = $adminPanelConfig;
        $this->userService = $userService;
        $this->siteId = $siteId;
    }

    /**
     * Get the Admin Menu Bar
     *
     * @return mixed
     */
    public function getAdminWrapperAction()
    {
        $allowed = $this->userService->isAllowed(
            'sites.' . $this->siteId,
            'admin',
            'Rcm\Acl\ResourceProvider'
        );

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

        $siteManager = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );

        $currentSiteId = $siteManager->getCurrentSiteId();

        /** @var RouteMatch $routeMatch */
        $routeMatch = $this->getEvent()->getRouteMatch();

        $sourcePageName = $routeMatch->getParam('page', 'index');
        $pageRevision = $routeMatch->getParam('revision', null);
        $pageType = $routeMatch->getParam('pageType', 'n');

        $resourceId = 'sites.' . $currentSiteId . '.pages.' . $pageType . '.'
            . $sourcePageName;

        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );
        //getting all set rules by resource Id
        $rules = $aclDataService->getRulesByResource($resourceId)->getData();
        
        if(count($rules) > 0) {
            $restrictions = true;
        } else {
            $restrictions = false;
        }

        return $restrictions;
    }

  }