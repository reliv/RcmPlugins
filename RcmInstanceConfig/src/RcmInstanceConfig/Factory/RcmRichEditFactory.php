<?php
/**
 * RcmRichEditFactory
 *
 * RcmRichEditFactory
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


use RcmInstanceConfig\ViewHelper\RcmEdit;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmRichEditFactory
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
class RcmRichEditFactory implements FactoryInterface
{
    /**
     * Creates this service
     *
     * @param ServiceLocatorInterface $serviceLocator zf2 service locator
     *
     * @return RcmEdit
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RcmEdit(
            $serviceLocator->getServiceLocator()->get(
                'RcmInstanceConfig/HtmlPurifier'
            ),
            true
        );
    }
}