<?php
/**
 * HtmlPurifierFactory
 *
 * HtmlPurifierFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmInstanceConfig\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * HtmlPurifierFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class HtmlPurifierFactory implements FactoryInterface
{
    /**
     * Creates this service
     *
     * @param ServiceLocatorInterface $serviceLocator zf2 service locator
     *
     * @return \HTMLPurifier
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', getcwd() . '/data/HTMLPurifier');
        return new \HTMLPurifier($config);
    }
} 