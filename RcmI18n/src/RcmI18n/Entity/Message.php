<?php
/**
 * Message.php
 *
 * Stores translations
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

namespace RcmI18n\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\ArrayObject;

/**
 * Message Entity
 *
 * Stores translations
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
 *
 * @ORM\Entity
 * @ORM\Table (
 *     name="rcmi18n_message",
 *     indexes={@ORM\Index(name="locale", columns={"locale"})}
 * )
 *
 * Doctrine won't do this but this is good to have:
 * CREATE UNIQUE INDEX localeDefaultText
 * ON rcmi18n_message (locale, defaultText(64));
 */
class Message implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var int Auto-Incremented Key - NOT TO BE USED BY ANYTHING BUT THE DB!
     *
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $messageId;

    /**
     * @var string Locale
     *
     * @ORM\Column(type="string", options={"default" = "en_US"})
     */
    protected $locale = 'en_US';

    /**
     * @var string Translation name
     *
     * @ORM\Column(type="string", length=512)
     */
    protected $defaultText;

    /**
     * @var string The translated message
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * getMessageId
     *
     * @return int
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * setMessageId
     *
     * @param $messageId
     *
     * @return void
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @param string $defaultText
     */
    public function setDefaultText($defaultText)
    {
        $this->defaultText = $defaultText;
    }

    /**
     * @return string
     */
    public function getDefaultText()
    {
        return $this->defaultText;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * getIterator
     *
     * @return array|\Traversable
     */
    public function getIterator()
    {
        $a = new ArrayObject($this->toArray());
        return $a->getIterator();
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
} 