<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RcmErrorHandler;

use RcmErrorHandler\Factory\RcmErrorHandlerFactory;
use RcmErrorHandler\Model\Config;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace(
                            '\\',
                            '/',
                            __NAMESPACE__
                        ),
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $em = $application->getEventManager();
        $sm = $application->getServiceManager();

        $config = $sm->get('\RcmErrorHandler\Config');

        $factory = new RcmErrorHandlerFactory($config, $e);

        if($config->get('overrideExceptions')) {

            $handler = $factory->getHandler();

            //handle the dispatch error (exception)
            $em->attach(
                \Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,
                [
                    $handler,
                    'handleEventException'
                ]
            );

            //handle the view render error (exception)
            $em->attach(
                \Zend\Mvc\MvcEvent::EVENT_RENDER_ERROR,
                [
                    $handler,
                    'handleEventException'
                ]
            );
        }

        $factory->buildListeners($em, $sm);

    }
}
