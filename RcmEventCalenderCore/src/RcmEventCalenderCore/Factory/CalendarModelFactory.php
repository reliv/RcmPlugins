<?php

namespace RcmEventCalenderCore\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RcmEventCalenderCore\Model\Calender;
 /**
 * CalendarFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   CalendarModelFactory
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

class CalendarModelFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new Calender(
            $serviceLocator->get('Doctrine\ORM\EntityManager')
        );
        return $service;
    }
}