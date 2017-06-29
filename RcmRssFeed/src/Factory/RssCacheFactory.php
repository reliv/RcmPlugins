<?php

namespace RcmRssFeed\Factory;

use Interop\Container\ContainerInterface;
use Zend\Cache\StorageFactory;
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
class RssCacheFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');

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
