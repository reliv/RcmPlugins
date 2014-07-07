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
}