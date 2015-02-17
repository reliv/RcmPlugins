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
        $navHtml = '<nav>';
        $navHtml .= $this->getUl($links, $admin, $id);
        $navHtml .= '</nav>';

        $selectHtml = $this->getSelect($links);

        return $navHtml."\n".$selectHtml;
    }

    /**
     * Select box used for responsive design
     *
     * @param Array $links Array of NavLinks
     *
     * @return string
     */
    protected function getSelect($links)
    {
        $view = $this->getView();

        $html = '<select>';
        $html .= '<option selected value="#">';
        $html .= $view->translate('Select a page:');
        $html .= '</option>';

        /** @var NavLink $link */
        foreach ($links as $link) {
            $html .= $this->getOption($link);
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Get a link option for the select box
     *
     * @param \RcmDynamicNavigation\Model\NavLink $link   Link to generate option for
     * @param string                              $spacer Place holder for spacers.  Used when called recursively
     *
     * @return string
     */
    protected function getOption(NavLink $link, $spacer = '')
    {
        $html = '<option value="'.$link->getHref().'"';

        $systemClass = $link->getSystemClass();

        if (!empty($systemClass)) {
            $html .= ' class="'.$systemClass.'"';
        }

        $html .= '>';

        $html .= $spacer.$link->getDisplay();
        $html .= '</option>';

        if ($link->hasLinks()) {
            $extraLinks = $link->getLinks();
            $newSpacer = $spacer.'--';

            foreach ($extraLinks as $extraLink) {
                $html .= $this->getOption($extraLink, $newSpacer);
            }
        }

        return $html;
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
        $html = '<ul';

        if (!empty($id)) {
            $html .= ' class="sf-menu" id="'.$id.'"';
        }

        $html .= '>'."\n";

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

        $permissionsArray = $link->getPermissions();

        $html = '<li';

        if (!empty($objectClass) || !empty($systemClass)) {
            $html .= ' class="'.$objectClass.' '.$systemClass.'"';

            if ($admin) {
                $html .= ' data-class="' . $objectClass . '"';
            }
        }

        if ($admin) {
            $html .= ' data-permissions="'.implode(',', $permissionsArray).'"';
        }

        $html .= '>'."\n";
        $html .= '<a href="'.$link->getHref().'"';

        if (!empty($target)) {
            $html .= ' target="'.$target.'"';
        }

        $html .= '>';
        $html .= $link->getDisplay();
        $html .= '</a>'."\n";

        if ($link->hasLinks()) {
            $html .= $this->getUl($link->getLinks(), $admin);
        }

        $html .= '</li>'."\n";

        return $html;
    }
}
