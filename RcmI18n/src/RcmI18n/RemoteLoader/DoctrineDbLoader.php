<?php
/**
 * Doctrine Db Loader
 *
 * Uses doctrine to grab translations from the DB that are compatible with ZF2 I18n
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18n\RemoteLoader;

use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;
use Zend\I18n\Translator\TextDomain;


/**
 * DoctrineDbLoader
 *
 * Uses doctrine to grab translations from the DB that are compatible with ZF2 I18n
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DoctrineDbLoader implements RemoteLoaderInterface
{

    protected $entityMgr;

    function __construct(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * Load translations from the DB
     *
     * @param  string $locale           example: en_US
     * @param  string $UnusedTextDomain not used
     *
     * @return \Zend\I18n\Translator\TextDomain
     */
    public function load($locale, $UnusedTextDomain = null)
    {
        $messages = $this->entityMgr->createQuery(
            'SELECT m.defaultText, m.text '
            . 'FROM RcmI18n\Entity\Message m '
            . 'WHERE m.locale = ?1'
        )->setParameter(1, $locale)->getArrayResult();

        $textDomain = new TextDomain();

        foreach ($messages as &$message) {
            $textDomain[$message['defaultText']] = utf8_encode($message['text']);
        }

        return $textDomain;
    }
} 