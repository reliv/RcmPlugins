<?php
namespace RcmAdmin\Entity;

use Rcm\Entity\Site;


/**
 * Class SiteReponse
 *
 * LongDescHere
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
class SiteResponse implements \JsonSerializable, \IteratorAggregate
{
    // Meta Data
    protected $code = 1;
    protected $message;

    // Object Data
    protected $siteId = null;
    protected $domain = null;
    protected $theme = null;
    protected $siteLayout = 'GuestSitePage';
    protected $siteTitle = null;
    protected $language = null;
    protected $country = null;
    protected $status = 'A';
    protected $favIcon = '/images/favicon.ico';
    protected $loginPage = '/login';
    protected $notAuthorizedPage = '/not-authorized';
    protected $notFoundPage = '/not-found';

    /**
     * getCode
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * setCode
     *
     * @param $code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = (int) $code;
    }

    /**
     * getMessage
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * setMessage
     *
     * @param $message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * getCountry
     *
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * setCountry
     *
     * @param string $country
     *
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * getDomain
     *
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * setDomain
     *
     * @param string $domain
     *
     * @return void
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * getFavIcon
     *
     * @return mixed
     */
    public function getFavIcon()
    {
        return $this->favIcon;
    }

    /**
     * setFavIcon
     *
     * @param string $favIcon
     *
     * @return void
     */
    public function setFavIcon($favIcon)
    {
        $this->favIcon = $favIcon;
    }

    /**
     * getLanguage
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * setLanguage
     *
     * @param string $language
     *
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * getLoginPage
     *
     * @return mixed
     */
    public function getLoginPage()
    {
        return $this->loginPage;
    }

    /**
     * setLoginPage
     *
     * @param string $loginPage
     *
     * @return void
     */
    public function setLoginPage($loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * getNotAuthorizedPage
     *
     * @return mixed
     */
    public function getNotAuthorizedPage()
    {
        return $this->notAuthorizedPage;
    }

    /**
     * setNotAuthorizedPage
     *
     * @param string $notAuthorizedPage
     *
     * @return void
     */
    public function setNotAuthorizedPage($notAuthorizedPage)
    {
        $this->notAuthorizedPage = $notAuthorizedPage;
    }

    /**
     * getNotFoundPage
     *
     * @return mixed
     */
    public function getNotFoundPage()
    {
        return $this->notFoundPage;
    }

    /**
     * setNotFoundPage
     *
     * @param mixed $notFoundPage
     *
     * @return void
     */
    public function setNotFoundPage($notFoundPage)
    {
        $this->notFoundPage = $notFoundPage;
    }

    /**
     * getSiteId
     *
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * setSiteId
     *
     * @param string $siteId
     *
     * @return void
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * getSiteLayout
     *
     * @return mixed
     */
    public function getSiteLayout()
    {
        return $this->siteLayout;
    }

    /**
     * setSiteLayout
     *
     * @param string $siteLayout
     *
     * @return void
     */
    public function setSiteLayout($siteLayout)
    {
        $this->siteLayout = $siteLayout;
    }

    /**
     * getSiteTitle
     *
     * @return mixed
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * setSiteTitle
     *
     * @param string $siteTitle
     *
     * @return void
     */
    public function setSiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;
    }

    /**
     * getStatus
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * setStatus
     *
     * @param string $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * getTheme
     *
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * setTheme
     *
     * @param string $theme
     *
     * @return void
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * populate
     *
     * @param array $data
     *
     * @return void
     */
    public function populate($data)
    {
        foreach ($data as $key => $val) {
            $methodName = 'set' . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($val);
            }
        }
    }

    /**
     * populateFromSite
     *
     * @param Site $site
     *
     * @return void
     */
    public function populateFromSite(Site $site)
    {
        $this->setSiteId($site->getSiteId());
        if (is_object($site->getDomain())) {
            $this->setDomain($site->getDomain()->getDomainName());
        }
        $this->setTheme($site->getTheme());
        $this->setSiteLayout($site->getSiteLayout());
        $this->setSiteTitle($site->getSiteTitle());
        if (is_object($site->getLanguage())) {
            $this->setLanguage($site->getLanguage()->getIso6392t());
        }
        if (is_object($site->getCountry())) {
            $this->setCountry($site->getCountry()->getIso3());
        }
        $this->setStatus($site->getStatus());
        $this->setFavIcon($site->getFavIcon());
        $this->setLoginPage($site->getLoginPage());
        $this->setNotAuthorizedPage($site->getNotAuthorizedPage());
        $this->setNotFoundPage($site->getNotFoundPage());
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(get_object_vars($this));
    }
} 