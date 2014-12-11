<?php
/**
 * Service Factory for the Rss Proxy Cache
 *
 * This file contains the factory needed to generate a Rss Proxy Cache.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmPlugins
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmRssFeed\Factory;

use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Rss Proxy Cache
 *
 * Factory for the Rss Proxy Cache.
 *
 * @category  Reliv
 * @package   RcmPlugins
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class RssCacheFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        $extraOptions = [
            'namespace' => 'rcmRssCache',
            'ttl' => '300'
        ];

        $cache = StorageFactory::factory(
            [
                'adapter' => [
                    'name' => $config['rcmCache']['adapter'],
                    'options' => $config['rcmCache']['options']
                        + $extraOptions,
                ],
                'plugins' => $config['rcmCache']['plugins'],
            ]
        );

        return $cache;
    }
}
