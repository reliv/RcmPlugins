<?php
/**
 * MvcTranslatorFactory.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   src\RcmI18n
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18n\Factory;

use RcmI18n\RemoteLoader\Database;
use Zend\Db\Adapter\Adapter;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * MvcTranslatorFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   src\RcmI18n
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class TranslatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $translator = Translator::factory($config['translator']);
        /**
         * Work-around for the translator loader plugin manager not having a config
         * key that it looks for.
         */
        foreach ($config['translator_plugins']['factories'] as $name => $factory) {
            $pluginManager = $translator->getPluginManager();
            $pluginManager->setServiceLocator($serviceLocator);
            $pluginManager->setFactory(
                $name, $factory
            );
        }
        return $translator;
    }
}