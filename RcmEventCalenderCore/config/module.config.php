<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmEventCalenderCore
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(

    'router' => array(
        'routes' => array (
            'rcm-event-calender-core-event' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-event-calender/events[/:id]',
                    'defaults' => array(
                        'controller' => 'EventAPIController',
                    )
                ),
            ),
            'rcm-event-calender-core-category' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-event-calender/categories[/:id]',
                    'defaults' => array(
                        'controller' => 'CategoryAPIController',
                    )
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'RcmEventCalenderCore' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/RcmEventCalenderCore/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmEventCalenderCore' => 'RcmEventCalenderCore'
                )
            )
        )
    ),

);