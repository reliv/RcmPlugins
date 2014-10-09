<?php

namespace RcmErrorHandler\EventManager;

/**
 * Class HandlerListenerInterface
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
interface HandlerListenerInterface
{

    /**
     * update
     *
     * @param \Zend\EventManager\Event $event
     *
     * @return mixed
     */
    public function update(\Zend\EventManager\Event $event);
} 