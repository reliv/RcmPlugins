<?php
/**
 * Nav link data model
 *
 * This file contains the nav link Data model for the Dynamic navigation plugin for the CMS
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmDynamicNavigation\Model;

/**
 * Nav link data model
 *
 * Data model for the Dynamic navigation plugin for the CMS
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class NavLink
{
    const LOGIN_CLASS = 'rcmDynamicNavigationLogin';
    const LOGOUT_CLASS = 'rcmDynamicNavigationLogout';
    const LOGIN_MAIN_CLASS = 'rcmDynamicNavigationAuthMenuItem';

    /** @var string */
    protected $href;

    /** @var array  */
    protected $class = array();

    /** @var array  */
    protected $systemClass = array();

    /** @var string */
    protected $target;

    /** @var string */
    protected $display;

    /** @var array  */
    protected $permissions = array();

    /** @var array  */
    protected $links = array();

    /**
     * Constructor
     *
     * @param array|null $data Initial Data array to populate object with
     */
    public function __construct(Array $data = null)
    {
        if (!empty($data) && is_array($data)) {
            $this->populate($data);
        }
    }

    /**
     * Populate object properties from data array
     *
     * @param array $data Data array to populate the object with
     *
     * @return void
     */
    public function populate(Array $data)
    {
        if (!empty($data['class'])) {
            $this->setClass($data['class']);
        }

        if (!empty($data['href'])) {
            $this->setHref($data['href']);
        }

        if (!empty($data['target'])) {
            $this->setTarget($data['target']);
        }

        if (!empty($data['display'])) {
            $this->setDisplay($data['display']);
        }

        if (!empty($data['permissions'])) {
            $this->setPermissions($data['permissions']);
        }

        if (!empty($data['links']) && is_array($data['links'])) {
            $this->setLinks($data['links']);
        }
    }

    /**
     * Get the link href
     *
     * @return string|null
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Se the link href
     *
     * @param string $href Link Href
     *
     * @return void
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * get the CSS class
     *
     * @return string
     */
    public function getClass()
    {
        return implode(" ", $this->class);
    }

    /**
     * Set the Css class
     *
     * @param mixed $class Css class
     *
     * @return void
     */
    public function setClass($class)
    {
        $this->class = array();

        $classes = explode(" ", $class);

        foreach ($classes as $classToAdd) {
            $this->addClass($classToAdd);
        }
    }

    /**
     * Add a css class
     *
     * @param string $class Css Class to add
     *
     * @return void
     */
    public function addClass($class)
    {
        if (!empty($class)) {
            $this->class[] = $class;
        }
    }

    /**
     * Get System Class
     *
     * @return string|null
     */
    public function getSystemClass()
    {
        return implode(" ", $this->systemClass);
    }

    /**
     * Set System Class
     *
     * @param string $class Class to set
     *
     * @return void
     */
    public function setSystemClass($class)
    {
        $this->systemClass = array();

        $classes = explode(" ", $class);

        foreach ($classes as $classToAdd) {
            $this->addSystemClass($classToAdd);
        }
    }

    /**
     * Add a system class.
     *
     * @param string $class Class to add
     *
     * @return void
     */
    public function addSystemClass($class)
    {
        if (!empty($class)) {
            $this->systemClass[] = $class;
        }
    }

    /**
     * Get the link Target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set the link target
     *
     * @param string $target Target for link
     *
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set the text to display
     *
     * @param mixed $display Text to display
     *
     * @return void
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * Get the link permissions
     *
     * @return Array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set the permission for the link
     *
     * @param string|Array $permissions Permissions
     *
     * @return void
     */
    public function setPermissions($permissions)
    {
        if (is_array($permissions)) {
            $this->permissions = $permissions;
            return;
        }

        $this->permissions = array();

        $permissionsArray = explode(',', $permissions);

        foreach ($permissionsArray as &$permissionItem) {
            $this->addPermission($permissionItem);
        }
    }

    /**
     * Add a permission
     *
     * @param string $permission Permission to add
     *
     * @return void
     */
    public function addPermission($permission)
    {
        $this->permissions[] = trim($permission);
    }



    /**
     * Get Sublinks
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set sublinks
     *
     * @param array $links Sublinks to add
     *
     * @return void
     */
    public function setLinks(Array $links)
    {
        $this->links = array();

        foreach ($links as &$link) {
            $this->addLink($link);
        }
    }

    /**
     * Add a link
     *
     * @param NavLink|array $link Link to add
     *
     * @return void
     */
    public function addLink($link)
    {
        if ($link instanceof self) {
            $this->links[] = $link;
        } else {
            $this->links[] = new self($link);
        }
    }

    /**
     * Is this a login link?
     *
     * @return bool
     */
    public function isLoginLink()
    {
        $class = $this->getClass();

        if (strpos($class, self::LOGIN_CLASS) === false) {
            return false;
        }

        return true;
    }

    /**
     * Is this a logout link?
     *
     * @return bool
     */
    public function isLogoutLink()
    {
        $class = $this->getClass();

        if (strpos($class, self::LOGOUT_CLASS) === false) {
            return false;
        }

        return true;
    }

    /**
     * Does this object have sub links?
     *
     * @return bool
     */
    public function hasLinks()
    {
        if (!empty($this->links)) {
            return true;
        }

        return false;
    }
}
