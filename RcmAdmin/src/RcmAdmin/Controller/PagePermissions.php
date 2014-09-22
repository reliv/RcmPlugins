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
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Controller;

use RcmUser\Acl\Service\AclDataService;
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
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class PagePermissions extends AbstractActionController
{

    protected $aclDataService;

    public function pagePermissionsAction() {

        $view = new ViewModel();
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
        $resourceId = 'sites.'.$currentSiteId.'.pages.'.$sourcePageName;
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

      $rules = $aclDataService->getRulesByResource($resourceId)->getData();
      $allRoles = $aclDataService->getAllRoles()->getData();
      // $view->setVariable('roles', $roles);


        $roleIds = array();
        $rolesHasRule = array();
        foreach($rules as $setRuleFor) {
            $rolesHasRule[] = $setRuleFor->getRoleId();
        }
        foreach ($allRoles as $role) {
           $roleId = $role->getRoleId();
            $roleIds[] = $roleId;
        }


//        print_r(array_intersect($roleIds, $rolesHasRule));
       print_r($roleIds);
       print_r($rolesHasRule);
       $view->setVariable('roles', $roleIds);
       $view->setVariable('rolesHasRules', $rolesHasRule);

       return $view;

    }

}
 