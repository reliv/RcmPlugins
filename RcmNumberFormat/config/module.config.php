<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.4
 *
 * LICENSE: New BSD License
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmNumberFormat
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(
    'router' => array(
        'routes' => array(
            'rcm-number-format-http-api-currency' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-number-format-http-api/currency/:number',
                    'defaults' => array(
                        'controller' => 'rcmNumberFormatController',
                        'action' => 'currency',
                    )
                ),
            ),
            'rcm-number-format-http-api-number' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-number-format-http-api/number/:number',
                    'defaults' => array(
                        'controller' => 'rcmNumberFormatController',
                        'action' => 'number',
                    )
                ),
            ),
        )
    ),
);