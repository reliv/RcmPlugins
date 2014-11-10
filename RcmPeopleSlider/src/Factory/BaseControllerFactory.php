<?php
namespace RcmPeopleSlider\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Rcm\Plugin\BaseController;


/**
 * BaseControllerFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
class BaseControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new BaseController(
            $serviceLocator->get('config'),
            'RcmPeopleSlider'
        );
        return $controller;
    }

}