<?php

namespace RcmErrorHandler\EventManager;

/**
 * Class HandlerListenerBase
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
class HandlerListenerBase implements HandlerListenerInterface
{

    /**
     * @var \RcmErrorHandler\Model\Config
     */
    public $options;

    /**
     * @param \RcmErrorHandler\Model\Config $options
     */
    public function __construct(
        \RcmErrorHandler\Model\Config $options
    ) {
        $this->options = $options;
    }

    /**
     * update
     *
     * @param \Zend\EventManager\Event $event
     *
     * @return void
     */
    public function update(\Zend\EventManager\Event $event)
    {
    }
} 