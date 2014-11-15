<?php
/**
 * SitesApiController.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Controller;

use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Site;
use Rcm\Exception\CountryNotFoundException;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\LanguageNotFoundException;
use Rcm\Exception\SiteNotFoundException;
use Rcm\Http\Response;
use RcmAdmin\Entity\SiteReponse;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * SitesApiController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @method boolean rcmIsAllowed($resourceId, $privilege = null, $providerId = 'Rcm\Acl\ResourceProvider')
 */
class ManageSitesApiController extends AbstractRestfulController
{
    /**
     * getEntityManger
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManger()
    {

        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        //ACCESS CHECK
        /** @todo Move to external modal */
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManger();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        $sitesObjects = $siteRepo->getSites(false);

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $domain = null; //'[no domains found for this site]';

            if (is_object($site->getDomain())) {
                $domain = $site->getDomain()->getDomainName();
            }

            // @todo use SiteResponse
            $sites[] = [
                'siteId' => $site->getSiteId(),
                'domain' => $domain,
                'active' => $site->getStatus(),
            ];
        }
        return new JsonModel($sites);
    }

    /**
     * update
     *
     * @param mixed $siteId
     * @param mixed $data
     *
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function update($siteId, $data)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        if (!is_array($data)) {
            throw new \Exception('Invalid data format');
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManger();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        if (!$siteRepo->isValidSiteId($siteId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->findOneBy(array('siteId' => $siteId));

        if ($data['active'] == 'D') {
            $site->setStatus('D');
        }
        if ($data['active'] == 'A') {
            $site->setStatus('A');
        }

        $entityManager->persist($site);
        $entityManager->flush();

        $data = $this->getSiteResponse($site);

        return new JsonModel($data);
    }

    /**
     * create - Create or Clone a site
     *
     * @param array $data - see getSiteResponse()
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        /* ACCESS CHECK */
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }
        /* */

        $siteRequest = new SiteReponse();
        $siteRequest->populate($data);

        // Get Site
        $newSite = $this->getSite($siteRequest->getSiteId());

        // Get Language
        $language = $this->getLanguage(
            $siteRequest->getLanguage(),
            $newSite->getLanguage()
        );

        if (empty($language)) {
            throw new LanguageNotFoundException('Language not found.');
        }

        // Get Country
        $country = $this->getCountry(
            $siteRequest->getCountry(),
            $newSite->getCountry()
        );

        if (empty($country)) {
            throw new CountryNotFoundException('Country not found.');
        }

        // Get Domain
        if (empty($siteRequest->getDomain())) {
            throw new DomainNotFoundException('Domain is required.');
        }

        $domain = $this->getDomain(
            $siteRequest->getDomain(),
            $newSite->getDomain()
        );

        if (empty($domain)) {
            $domain = $this->createDomain(
                $siteRequest->getDomain(),
                $newSite->getLanguage()->getIso6392t()
            );
        }

        // Setup site
        $newSite->setLanguage($language);
        $newSite->setCountry($country);
        $newSite->setDomain($domain);

        // Save
        $newSite = $this->cloneCreateSite($newSite);

        $data = $this->getSiteResponse($newSite);

        return new JsonModel($data);
    }

    /**
     * Clone site if exists, else make new site
     *
     * @param $siteId
     *
     * @return Site
     */
    protected function getSite($siteId)
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
    protected function getCountry(
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
    protected function getLanguage(
        $iso6392tLanguageCode,
        $default = null
    ) {
        if (empty($iso6392tLanguageCode)) {

            return $default;
        }

        $repo = $this->getEntityManger()->getRepository('\Rcm\Entity\Language');

        try {

            $result = $repo->findOneBy(array('iso639_2t' => $iso6392tLanguageCode));
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
    protected function getDomain($domainName, $default = null)
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
     */
    protected function createDomain($domainName, $defaultLanguage)
    {
        if (empty($domainName)) {
            throw new DomainNotFoundException('Domain is required.');
        }

        $domain = new Domain();
        $domain->setDomainName($domainName);
        $domain->setDefaultLanguage($defaultLanguage);

        $repo = $this->getEntityManger()->getRepository('\Rcm\Entity\Domain');
        $repo->getDoctrine()->persist($domain);

        return $domain;
    }

    /**
     * cloneCreateSite
     *
     * @param Site $newSite
     *
     * @return Site
     */
    protected function saveSite(Site $newSite)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManger();

        $entityManager->persist($newSite);

        $entityManager->flush();

        return $site;
    }

    /**
     * getSiteResponse
     *
     * @param Site $site
     *
     * @return array
     */
    protected function getSiteResponse(Site $site)
    {
        $siteResponse = new SiteReponse();
        $siteResponse->populateFromSite($site);
        return $siteResponse;
    }


}