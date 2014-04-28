<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_I18n
 */

namespace RcmI18n\RemoteLoader;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Sql\Sql;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;
use Zend\I18n\Translator\Plural\Rule as PluralRule;
use Zend\I18n\Translator\TextDomain;

/**
 * Database loader.
 *
 * @category   Zend
 * @package    Zend_I18n
 * @subpackage Translator
 */
class Database implements RemoteLoaderInterface
{
    /**
     * Database adapter.
     *
     * @var DbAdapter
     */
    protected $dbAdapter;

    /**
     * Create a new database loader.
     *
     * @param DbAdapter $dbAdapter
     */
    public function __construct(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * load(): defined by RemoteLoaderInterface.
     *
     * @see    RemoteLoaderInterface::load()
     * @param  string $locale
     * @param  string $textDomain
     * @return TextDomain
     * @throws Exception\InvalidArgumentException
     */
    public function load($locale, $textDomain)
    {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();
        $select->from('zend_i18n_translator_locales');
        $select->columns(array('locale_plural_forms'));
        $select->where(array('locale_id' => $locale));

        $localeInformation = $this->dbAdapter->query(
            $sql->getSqlStringForSqlObject($select),
            DbAdapter::QUERY_MODE_EXECUTE
        );

        if (!count($localeInformation)) {
            return $textDomain;
        }

        $localeInformation = $localeInformation->current();

        $textDomain->setPluralRules(
            PluralRule::fromString($localeInformation['locale_plural_forms'])
        );

        $select = $sql->select();
        $select->from('zend_i18n_translator_messages');
        $select->columns(array(
                'message_key',
                'message_translation',
                'message_plural_index'
            ));
        $select->where(array(
                'locale_id'      => $locale,
                'message_domain' => $textDomain
            ));

        $messages = $this->dbAdapter->query(
            $sql->getSqlStringForSqlObject($select),
            DbAdapter::QUERY_MODE_EXECUTE
        );

        $textDomain = new TextDomain();

        foreach ($messages as $message) {
            if (isset($textDomain[$message['message_key']])) {
                if (!is_array($textDomain[$message['message_key']])) {
                    $textDomain[$message['message_key']] = array(
                        $message['message_plural_index'] => $textDomain[$message['message_key']]
                    );
                }

                $textDomain[$message['message_key']][$message['message_plural_index']]
                    = $message['message_translation'];
            } else {
                $textDomain[$message['message_key']] = $message['message_translation'];
            }
        }

        return $textDomain;
    }
}