<?php

namespace DomainRedirector\EventListener;

use Zend\Mvc\MvcEvent;

class DomainRedirectListener
{
    protected $domainRedirects = [];

    public function __construct($config)
    {
        $this->domainRedirects = $config['DomainRedirector']['domainRedirects'];
    }

    /**
     * Redirect old assets on sites that we do not control to new AWS urls
     */
    public function routeEvent(MvcEvent $event)
    {
        $host = $event->getRequest()->getUri()->getHost();
        if (!isset($this->domainRedirects[$host])) {
            return null;
        }
        $event->getResponse()->setStatusCode(301);

        /**
         * @var $headers \Zend\Http\Headers
         */
        $headers = $event->getResponse()->getHeaders();
        $headers->addHeaderLine(
            'location',
            $this->domainRedirects[$host] .
            $event->getRequest()->getUri()->getPath()
        );

        return $event->getResponse();
    }
}
