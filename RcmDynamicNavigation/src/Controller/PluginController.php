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
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace RcmDynamicNavigation\Controller;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Site;
use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use RcmDynamicNavigation\Model\NavLink;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PluginController extends BaseController implements PluginInterface
{
    /** @var CmsPermissionChecks */
    protected $permissionChecks;

    /** @var Site  */
    protected $currentSite;

    /**
     * Constructor
     *
     * @param CmsPermissionChecks $permissionChecks CmsPermission service
     * @param Site                $currentSite      Current site needed for permissions checks
     * @param null                $config           System config
     */
    public function __construct(
        CmsPermissionChecks $permissionChecks,
        Site $currentSite,
        $config
    ) {
        $this->permissionChecks = $permissionChecks;
        $this->currentSite = $currentSite;
        parent::__construct($config, 'RcmDynamicNavigation');
    }

    /**
     * Render the plugin
     *
     * @param int   $instanceId     Instance ID
     * @param array $instanceConfig Instance Config
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $links = array();

        if (!empty($instanceConfig['links']) && is_array($instanceConfig['links'])) {
            foreach ($instanceConfig['links'] as $link) {
                $links[] = new NavLink($link);
            }
        }

        $this->checkLinks($links);

        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $view->setVariable('links', $links);
        $view->setVariable('isAdmin', $this->permissionChecks->siteAdminCheck($this->currentSite));
        return $view;
    }

    /**
     * Check the links for display
     *
     * @param Array $links Array of links to check
     *
     * @return void
     */
    public function checkLinks(&$links)
    {
        if (empty($links)) {
            return;
        }

        /**
         * @var integer $index
         * @var NavLink $link
         */
        foreach ($links as $index => $link) {
            if (!$this->checkLink($link)) {
                unset($links[$index]);
            }

            if ($link->hasLinks()) {
                $subLinks = $link->getLinks();
                $this->checkLinks($subLinks);
                $link->setLinks($subLinks);
            }
        }
    }

    /**
     * Check an individual link
     *
     * @param NavLink $link Link to check
     *
     * @return bool
     */
    public function checkLink(NavLink $link)
    {
        $siteAdmin = $this->permissionChecks->siteAdminCheck($this->currentSite);
        $userHasPermissions = $this->usersRoleHasPermissions($link->getPermissions());

        if ($link->isLoginLink() && $this->permissionChecks->isCurrentUserLoggedIn()) {
            $link->addSystemClass('HiddenLink');
        } elseif ($link->isLogoutLink() && !$this->permissionChecks->isCurrentUserLoggedIn()) {
            $link->addSystemClass('HiddenLink');
        } elseif ($siteAdmin && !$userHasPermissions) {
            $link->addSystemClass('HiddenLink');
        }

        if ($siteAdmin || $userHasPermissions) {
            return true;
        }

        return false;
    }

    /**
     * Does the user have permissions to see this link?
     *
     * @param array $permittedRoles Roles to check
     *
     * @return bool
     */
    public function usersRoleHasPermissions(Array $permittedRoles)
    {
        if (empty($permittedRoles)) {
            return true;
        }

        foreach ($permittedRoles as $role) {
            if ($this->permissionChecks->hasRoleBasedAccess($role)) {
                return true;
            }
        }

        return false;
    }
}
