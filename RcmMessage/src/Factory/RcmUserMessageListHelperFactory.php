<?php


namespace RcmMessage\Factory;

use RcmMessage\View\Helper\RcmUserMessageListHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class RcmUserMessageListHelperFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserMessageListHelperFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();

        $userMessageRepo = $serviceLocator->get('Doctrine\ORM\EntityManager')->getRepository('\RcmMessage\Entity\UserMessage');
        $rcmUserService = $serviceLocator->get('RcmUser\Service\RcmUserService');
        $translator = $serviceLocator->get('MvcTranslator');

        return new RcmUserMessageListHelper(
            $userMessageRepo,
            $rcmUserService,
            $translator,
            $serviceLocator->get('RcmHtmlPurifier')
        );
    }
}