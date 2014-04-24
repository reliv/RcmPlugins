<?php
/**
 * Factory  for Include Jquery View helper
 *
 * This factory biulds the view helper that includes jquery, jqueryui, and block-ui
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmJquery\Factory;

use Rcm\View\Helper\Container;
use RcmJquery\View\Helper\IncludeJquery;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory  for Include Jquery View helper
 *
 * This factory biulds the view helper that includes jquery, jqueryui, and block-ui
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmJquery
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class IncludeJqueryFactory implements FactoryInterface
{

    /**
     * Creates the service
     *
     * @param ServiceLocatorInterface $viewServiceManager zf2 service locator
     *
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {

        return new IncludeJquery();
    }
}
