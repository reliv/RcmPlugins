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
 *     indexes={@ORM\Index(name="locale", columns={"locale"})}),
 * )
 */
class Message
{
    /**
     * @var string Locale
     *
     * @ORM\Id
     * @ORM\Column(type="string", options={"default" = "en_US"})
     */
    protected $locale = 'en_US';

    /**
     * @var string Translation name
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $defaultText;

    /**
     * @var string The translated message
     *
     * @ORM\Column(type="string")
     */
    protected $text;

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
} 