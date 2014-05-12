<?php
 /**
 * RcmTwitterBootstrapIncludeViewHelperFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmTwitterBootstrap\Factory;

use RcmTwitterBootstrap\View\Helper\IncludeTwitterBootstrap;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RcmTwitterBootstrapIncludeViewHelperFactory
 *
 * RcmTwitterBootstrapIncludeViewHelperFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmTwitterBootstrapIncludeViewHelperFactory implements \Zend\ServiceManager\FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $viewServiceManager
     * @return Container
     */
    public function createService(ServiceLocatorInterface $viewServiceManager)
    {
        return new IncludeTwitterBootstrap();
    }
}