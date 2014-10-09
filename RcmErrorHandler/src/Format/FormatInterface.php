<?php

namespace RcmErrorHandler\Format;

use RcmErrorHandler\Model\GenericError;

/**
 * Class FormatInterface
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
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface FormatInterface
{

    /**
     * getString
     *
     * @param GenericError $error
     *
     * @return string
     */
    public function getString(GenericError $error);

    /**
     * getBasicString - no details exposed - public friendly
     *
     * @param GenericError $error
     *
     * @return string
     */
    public function getBasicString(GenericError $error);

    /**
     * getTraceString
     *
     * @param GenericError $error
     * @param int          $options
     * @param int          $limit
     *
     * @return string
     */
    public function getTraceString(GenericError $error, $options = 3, $limit = 0);

    /**
     * displayString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayString(GenericError $error, \Zend\Mvc\MvcEvent $event);

    /**
     * displayBasicString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayBasicString(GenericError $error, \Zend\Mvc\MvcEvent $event);

    /**
     * displayTraceString
     *
     * @param GenericError       $error
     * @param \Zend\Mvc\MvcEvent $event
     *
     * @return void
     */
    public function displayTraceString(GenericError $error, \Zend\Mvc\MvcEvent $event);
} 