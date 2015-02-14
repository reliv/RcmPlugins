<?php

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace RcmDynamicNavigation\Controller;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Site;
use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class PluginController
    extends BaseController
    implements PluginInterface
{
    /**
     * @var CmsPermissionChecks
     */
    protected $permissionChecks;

    protected $currentSite;


    /**
     * Constructor
     *
     * @param CmsPermissionChecks $permissionChecks
     * @param null $config
     */
    function __construct(
        CmsPermissionChecks $permissionChecks,
        Site $currentSite,
        $config
    ) {
        $this->permissionChecks = $permissionChecks;
        $this->currentSite = $currentSite;
        parent::__construct($config, 'RcmDynamicNavigation');
    }

    public function renderInstance($instanceId, $instanceConfig)
    {
        $this->checkLinks($instanceConfig['links']);

        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        return $view;
    }

    public function checkLinks(&$links)
    {
        if (empty($links) || !is_array($links)) {
            return;
        }

        foreach ($links as $index => &$link) {

            if (!empty($link['links'])) {
                $this->checkLinks($link['links']);
            }

            if (empty($link['permissions'])) {
                $link['permissions'] = '';
            }

            if (empty($link['system_class'])) {
                $link['system_class'] = '';
            }

            $siteAdmin = $this->permissionChecks->siteAdminCheck($this->currentSite);
            $userHasPermissions = $this->usersRoleHasPermissions($link['permissions']);

            if ($this->isLoginLink($link) && $this->permissionChecks->isCurrentUserLoggedIn()) {
                $link['system_class'] .= ' HiddenLink';
            } elseif ($this->isLogoutLink($link) && !$this->permissionChecks->isCurrentUserLoggedIn()) {
                print "is logout";
                $link['system_class'] .= ' HiddenLink';
            } elseif ($siteAdmin && !$userHasPermissions) {
                $link['system_class'] .= ' HiddenLink';
            } elseif (!$siteAdmin && !$userHasPermissions) {
                unset($links[$index]);
            }
        }
    }

    public function isLoginLink(&$link)
    {
        if (empty($link['class'])) {
            return false;
        }

        if (strpos($link['class'], 'rcmDynamicNavigationLogin') === false) {
            return false;
        }

        return true;
    }

    public function isLogoutLink(&$link)
    {
        if (empty($link['class'])) {
            return false;
        }

        if (strpos($link['class'], 'rcmDynamicNavigationLogout') === false) {
            return false;
        }

        return true;
    }

    public function usersRoleHasPermissions($permittedRoles)
    {
        $permittedRoles = trim($permittedRoles);
        $rolesToCheck = explode(',',$permittedRoles);

        foreach ($rolesToCheck as $index => &$value) {
            $value = trim($value);

            if (empty($value)) {
                unset($rolesToCheck[$index]);
            }
        }

        if (empty($rolesToCheck[0])) {
            return true;
        }

        foreach ($rolesToCheck as $role) {
            if ($this->permissionChecks->hasRoleBasedAccess($role)) {
                return true;
            }
        }

        return false;
    }
}
