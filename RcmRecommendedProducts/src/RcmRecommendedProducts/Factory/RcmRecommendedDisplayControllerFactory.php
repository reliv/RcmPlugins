<?php
 /**
 * RcmRecommendedProductsDisplayControllerFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmRecommendedProducts\Factory
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmRecommendedProducts\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * RcmRecommendedProductsDisplayControllerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmRecommendedProducts\Factory
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class RcmRecommendedDisplayControllerFactory implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $controllerMgr
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $controllerMgr)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $cm For IDE */
        $cm = $controllerMgr;
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceMgr = $cm->getServiceLocator();

        return $serviceMgr->get('RcmRecommendedProducts');
    }
}