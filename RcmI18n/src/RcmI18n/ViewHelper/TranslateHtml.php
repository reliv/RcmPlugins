<?php
/**
 * Purifying & translating view helper
 *
 * Works like ZF2's translate view helper but it also filters web script out of the
 * translation since they came from the DB and can't be trusted not to contain
 * XSS attacks. This filtering allows translations to contain things like <br> tags
 * that would not normally make it through an escapeHtml() call.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmInstanceConfig\ViewHelper;

use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Purifying & translating view helper
 *
 * Works like ZF2's translate view helper but it also filters web script out of the
 * translation since they came from the DB and can't be trusted not to contain
 * XSS attacks. This filtering allows translations to contain things like <br> tags
 * that would not normally make it through an escapeHtml() call..
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class TranslateHtml extends AbstractTranslatorHelper implements
    TranslatorAwareInterface
{
    protected $htmlPurifier;
    protected $editAttributeKey;

    /**
     * RcmEdit Constructor
     *
     * @param \HTMLPurifier $htmlPurifier html purifier class for striping web script
     */
    function __construct(\HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    /**
     * Translate a message and purify it to remove web scripts
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     *
     * @throws \RuntimeException
     * @return string
     */
    public function __invoke($message, $textDomain = null, $locale = null)
    {
        $translator = $this->getTranslator();
        if (null === $translator) {
            throw new \RuntimeException('Translator has not been set');
        }
        if (null === $textDomain) {
            $textDomain = $this->getTranslatorTextDomain();
        }

        return $this->htmlPurifier->purify(
            $translator->translate($message, $textDomain, $locale)
        );
    }
} 