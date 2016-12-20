<?php
/**
 * Module Config For ZF2
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   App
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace DomainRedirector;

use App\Factory\DoctrineInjector;
use App\Listener\DiscountLevelListener;
use App\Listener\OrderManagerInitAuthListener;
use App\Listener\RenewalAuthListener;
use DomainRedirector\EventListener\DomainRedirectListener;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   App
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Module
{
    /**
     * Bootstrap For RCM.
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return null
     */
    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        if ($serviceManager->get('request') instanceof ConsoleRequest) {
            return;
        }

        // Check for redirects from the CMS
        $event->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_ROUTE,
            [
                new DomainRedirectListener($serviceManager->get('Config')),
                'routeEvent'
            ],
            10001
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
