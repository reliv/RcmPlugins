<?php return [
    'DomainRedirector' => [
        'domainRedirects' => [
//            'hi.domain.com' => 'https://s3-us-west-2.amazonaws.com/hi',
        ]
    ],
    'service_manager' => [
        'config_factories' => [
            'DomainRedirector\EventListener\DomainRedirectListener' => [
                'arguments' => [
                    'config'
                ]
            ],
        ]
    ]
];
