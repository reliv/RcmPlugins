<?php

namespace RcmI18n\Factory;

use RcmI18n\Model\Locales;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * LocalesFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   src\RcmI18n
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class LocalesFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Locales(
            $serviceLocator->get('Doctrine\ORM\EntityManager')->getRepository('\Rcm\Entity\Site')
        );
    }
}