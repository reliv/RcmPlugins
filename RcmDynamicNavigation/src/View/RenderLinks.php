<?php
/**
 * Render Links
 *
 * This file contains the render links view helper
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

namespace RcmDynamicNavigation\View;

use Zend\View\Helper\AbstractHelper;
use RcmDynamicNavigation\Model\NavLink;

/**
 * Render links
 *
 * Render a collection of NavLinks
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RenderLinks extends AbstractHelper
{
    /**
     * Render the links
     *
     * @param Array   $links Array of NavLinks
     * @param boolean $admin Render in admin mode
     * @param string  $id    Id to pass to container
     *
     * @return string
     */
    public function __invoke($links, $admin, $id)
    {
        return $this->render($links, $admin, $id);
    }

    /**
     * Render Method
     *
     * @param Array   $links Array of NavLinks
     * @param boolean $admin Render in admin mode
     * @param string  $id    Id to pass to container
     *
     * @return string
     */
    public function render($links, $admin, $id)
    {
        $navHtml = '<nav class="navbar navbar-default">';
        $navHtml .= '
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#'.$id.'" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div id="'.$id.'" class="navbar-collapse collapse">
          ';
        $navHtml .= $this->getUl($links, $admin, $id);
        $navHtml .= '</div></nav>';

        return $navHtml;
    }

    /**
     * Get the UL container for links
     *
     * @param Array   $links Array of NavLinks
     * @param boolean $admin Render in admin mode
     * @param string  $id    Id to pass to container
     *
     * @return string
     */
    protected function getUl($links, $admin, $id = null)
    {
        $html = '';

        if (!empty($id)) {
            $html .= '<ul class="nav navbar-nav">';
        } else {
            $html .= '<ul class="dropdown-menu" role="menu">';
        }

        foreach ($links as $link) {
            $html .= $this->getLi($link, $admin);
        }

        $html .= '</ul>'."\n";

        return $html;
    }

    /**
     * Get the li and link html for a link
     *
     * @param \RcmDynamicNavigation\Model\NavLink $link  Link to render
     * @param boolean                             $admin Render in admin mode
     *
     * @return string
     */
    protected function getLi(NavLink $link, $admin)
    {
        $target = $link->getTarget();

        $objectClass = $link->getClass();
        $systemClass = $link->getSystemClass();

        if ($link->hasLinks()) {
            $objectClass .= ' dropdown';
        }

        $permissionsArray = $link->getPermissions();

        $html = '<li';

        if (!empty($objectClass) || !empty($systemClass)) {
            $html .= ' class="'.$objectClass.' '.$systemClass.'"';
        }

        if ($admin) {
            $html .= ' data-permissions="'.implode(',', $permissionsArray).'"';
        }

        $html .= '>'."\n";
        $html .= '<a href="'.$link->getHref().'"';

        if ($link->hasLinks()) {
            $html .= 'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"';
        }

        if (!empty($target)) {
            $html .= ' target="'.$target.'"';
        }

        $html .= '>';
        $html .= '<span class="linkText">'.$link->getDisplay().'</span>';

        if ($link->hasLinks()) {
            $html .= '<span class="caret"></span>';
        }

        $html .= '</a>'."\n";

        if ($link->hasLinks()) {
            $html .= $this->getUl($link->getLinks(), $admin);
        }

        $html .= '</li>'."\n";

        return $html;
    }

    protected function getMobileMenu()
    {
        $html = '<div class="glyphicon glyphicon-menu-hamburger mobileMenuIcon" aria-hidden="true"></div>';
        return $html;
    }
}
