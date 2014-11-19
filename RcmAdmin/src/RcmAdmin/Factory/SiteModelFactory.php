<?php


namespace RcmAdmin\Factory;

use RcmAdmin\Model\SiteModel;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class SitModelFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteModelFactory implements FactoryInterface
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SiteModel
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SiteModel($serviceLocator->get('config'));
    }
} 