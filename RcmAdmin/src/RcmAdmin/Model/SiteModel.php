<?php

namespace RcmAdmin\Model;

use Doctrine\ORM\EntityManagerInterface;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\SiteNotFoundException;

/**
 * Class SiteModel
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
class SiteModel {

    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * getEntityManger
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManger()
    {
        return $this->entityManager;
    }

    /**
     * getSite
     *
     * @param $siteId
     *
     * @return Site
     */
    public function getNewSite($siteId = null)
    {
        if (empty($siteId)) {

            // new site
            /** @var \Rcm\Entity\Site $newSite */
            return new Site();
        }

        // clone
        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManger()->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Entity\Site $site */
        $existingSite = $siteRepo->find($siteId);

        if (empty($existingSite)) {

            throw new SiteNotFoundException("Site {$siteId} not found.");
        }

        $site = clone($existingSite);

        return $site;
    }

    /**
     * getCountry
     *
     * @param      $iso3CountryCode
     * @param null $default
     *
     * @return null|object
     */
    public function getCountry(
        $iso3CountryCode,
        $default = null
    ) {
        if (empty($iso3CountryCode)) {

            return $default;
        }

        $repo = $this->getEntityManger()->getRepository('\Rcm\Entity\Country');

        try {

            $result = $repo->findOneBy(array('iso3' => $iso3CountryCode));
        } catch (NoResultException $e) {

            $result = $default;
        }

        return $result;
    }

    /**
     * getLanguage
     *
     * @param      $iso6392tLanguageCode
     * @param null $default
     *
     * @return null|object
     */
    public function getLanguage(
        $iso6392tLanguageCode,
        $default = null
    ) {
        if (empty($iso6392tLanguageCode)) {

            return $default;
        }

        $repo = $this->getEntityManger()->getRepository('\Rcm\Entity\Language');

        try {

            $result = $repo->findOneBy(array('iso639_2t' => $iso6392tLanguageCode));
            $this->getEntityManger()->persist($result);
        } catch (NoResultException $e) {

            $result = $default;
        }

        return $result;
    }

    /**
     * getDomain
     *
     * @param      $domainName
     * @param null $default
     *
     * @return null|object
     */
    public function getDomain($domainName, $default = null)
    {
        if (empty($domainName)) {

            return $default;
        }

        $repo = $this->getEntityManger()->getRepository('\Rcm\Entity\Domain');

        try {
            $result = $repo->findOneBy(array('domain' => $domainName));
        } catch (NoResultException $e) {
            $result = $default;
        }

        return $result;
    }

    /**
     * createDomain
     *
     * @param $domainName
     * @param $defaultLanguage
     *
     * @return Domain
     * @throws DomainNotFoundException
     */
    public function createDomain($domainName, $defaultLanguage)
    {
        if (empty($domainName)) {
            throw new DomainNotFoundException('Domain is required.');
        }

        // Check if exists first
        $existingDomain = $this->getDomain($domainName);
        if(!empty($existingDomain)){
            throw new DomainNotFoundException('New domain is required.');
        }

        $domain = new Domain();
        $domain->setDomainName($domainName);
        $domain->setDefaultLanguage($defaultLanguage);

        $this->getEntityManger()->persist($domain);

        return $domain;
    }

    /**
     * cloneCreateSite
     *
     * @param Site $newSite
     *
     * @return Site
     */
    public function saveSite(Site $newSite)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManger();

        $entityManager->persist($newSite);

        $entityManager->flush();

        return $newSite;
    }

    /**
     * createDefaultPages
     *
     * @param Site $site
     *
     * @return void
     */
    public function createDefaultPages(Site $site, $author)
    {

        $defaultPages = [
            'login' => [
                'decription' => 'Login Page.',
                'pageTitle' => 'Login',
            ],
            'not-authorized' => [
                'decription' => 'Not Authorized Page.',
                'pageTitle' => 'Not Authorized',
            ],
            'not-found' => [
                'decription' => 'Not Found Page.',
                'pageTitle' => 'Not Found',
            ],
        ];

        foreach($defaultPages as $key => $config){

            $page = new Page();
            $page->setSite($site);
            $page->setName($key);
            $page->setDescription($config['decription']);
            $page->setPageTitle($config['pageTitle']);
            $page->setAuthor($author);

            $site->addPage($page);
        }

    }


} 