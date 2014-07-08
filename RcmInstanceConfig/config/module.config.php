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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(

    'doctrine' => array(
        'driver' => array(
            'RcmInstanceConfig' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/RcmInstanceConfig/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmInstanceConfig' => 'RcmInstanceConfig'
                )
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'rcmTextEdit' => 'RcmInstanceConfig\Factory\RcmTextEditFactory',
            'rcmRichEdit' => 'RcmInstanceConfig\Factory\RcmRichEditFactory',
        )
    ),
);