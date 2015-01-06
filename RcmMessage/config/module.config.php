<?php
/**
 * Config
 */
return [
    /**
     *
     */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-message/' => __DIR__ . '/../public/',
            ],
        ],
    ],
    /**
     *
     */
    'router' => [
        'routes' => [
            'RcmMessageList' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/my-system-messages',
                    'defaults' => [
                        'controller' => 'RcmMessage\Controller\MessageListController',
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    /**
     *
     */
    'controllers' => [
        'invokables' => [
            'RcmMessage\Controller\MessageListController' => 'RcmMessage\Controller\MessageListController',
        ],
    ],
    /**
     *
     */
    'view_helpers' => [
        'invokables' => [
            // 'rmcMessageIcon' => 'RcmMessage\View\Helper\RcmMessageIcon',
        ],
    ],
    /**
     *
     */
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ],
    ],
    /**
     *
     */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    /**
     *
     */
    'doctrine' => array(
        'driver' => array(
            'RcmMessage' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Entity',
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'RcmMessage' => 'RcmMessage'
                )
            )
        )
    ),
];