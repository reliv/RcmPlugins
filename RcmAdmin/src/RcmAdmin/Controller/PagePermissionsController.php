<?php
/**
 * PagePermissions.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


/**
 * PagePermissions
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class PagePermissionsController extends AbstractActionController
{
    /**
     * @var \RcmUser\Acl\Service\AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * Getting all Roles list and rules if role has one
     *
     * @return ViewModel
     */
    public function pagePermissionsAction()
    {

        $view = new ViewModel();
        //fixes rendering site's header and footer in the dialog
        $view->setTerminal(true);

        $siteManager = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );

        $currentSiteId = $siteManager->getCurrentSiteId();

        $sourcePageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        $resourceId = 'sites.' . $currentSiteId . '.pages.' . $pageType . '.'
            . $sourcePageName;
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );
        //getting all set rules by resource Id
        $rules = $aclDataService->getRulesByResource($resourceId)->getData();

        //getting list of all dynamically created roles
        $allRoles = $aclDataService->getAllRoles()->getData();


        $roleIds = array();
        $rolesHasRule = array();
        foreach ($rules as $setRuleFor) {
            $rolesHasRule[] = $setRuleFor->getRoleId();
        }
        foreach ($allRoles as $role) {
            $roleId = $role->getRoleId();
            $roleIds[] = $roleId;
        }

        $view->setVariable('roles', $roleIds);
        $view->setVariable('rolesHasRules', $rolesHasRule);

        $view->setVariable(
            'rcmPageName',
            $sourcePageName
        );
        $view->setVariable(
            'rcmPageType',
            $pageType
        );

        return $view;

    }

}
 