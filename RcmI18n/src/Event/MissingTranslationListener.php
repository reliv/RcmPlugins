<?php

namespace RcmI18n\Event;

use Doctrine\ORM\EntityManager;
use RcmI18n\Entity\Message;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AddMissingTranslationListener
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class MissingTranslationListener implements ListenerAggregateInterface
{
    const DO_NOT_TRANSLATE = 'DO_NOT_TRANSLATE';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @param array         $config
     * @param EntityManager $entityManager
     */
    public function __construct($config, EntityManager $entityManager)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    /**
     * getConfig
     *
     * @return array|object
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * getDefaultLocale
     *
     * @return array
     */
    protected function getDefaultLocale()
    {
        $config = $this->getConfig();
        return $config['RcmI18n']['defaultLocale'];
    }

    /**
     * getEntityManager
     *
     * @return array
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(
            Translator::EVENT_MISSING_TRANSLATION,
            array(
                $this,
                'addMissingDefaultTranslation'
            )
        );
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {

    }

    /**
     * addMissingDefaultTranslation
     *
     * @param $event
     *
     * @return void
     */
    public function addMissingDefaultTranslation($event)
    {
        $params = $event->getParams();

        $defaultLocale = $this->getDefaultLocale();

        // Ignore if not translate
        if($params['text_domain'] === self::DO_NOT_TRANSLATE) {

            return;
        }

        // Only adding if we are the default locale
        if($params['locale'] !== $defaultLocale) {

            return;
        }

        $em = $this->getEntityManager();

        try {
            $defaultMessage = $em->getRepository('RcmI18n\Entity\Message')
                ->findOneBy(
                    [
                        'locale' => $defaultLocale,
                        'defaultText' => $params['message']
                    ]
                );
        } catch (\Exception $e) {
            $defaultMessage = null;
        }

        if (empty($defaultMessage)) {

            $newMessage = new Message();
            $newMessage->setLocale($defaultLocale);
            $newMessage->setDefaultText($params['message']);
            $newMessage->setText($params['message']);

            $em->persist($newMessage);
            $em->flush($newMessage);
        }
    }

    /**
     * emailMissingTranslation
     *
     * @param $event
     *
     * @return void
     */
    public function emailMissingTranslation($event)
    {
        $params = $event->getParams();

        $defaultLocale = $this->getDefaultLocale();

        if($params['locale'] !== $defaultLocale) {
            // @todo write and implement this
        }
    }
}