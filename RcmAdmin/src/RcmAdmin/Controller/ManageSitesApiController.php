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

use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use Rcm\View\Model\RcmJsonModel;
use RcmAdmin\Entity\SiteApiRequest;
use RcmAdmin\Entity\SiteApiResponse;
use RcmAdmin\Entity\SiteResponse;
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
    protected $defaultPages = [
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

    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * getSiteModel
     *
     * @return \RcmAdmin\Model\SiteModel
     */
    protected function getSiteModel()
    {
        return $this->serviceLocator->get('RcmAdmin\Model\SiteModel');
    }

    /**
     * getCurrentUser
     *
     * @return \RcmUser\User\Entity\User
     */
    protected function getCurrentUser()
    {
        return $this->rcmUserGetCurrentUser();
    }

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
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

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        $sitesObjects = $siteRepo->getSites(false);

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $sites[] = $this->buildSiteApiResponse($site);
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
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        if (!$siteRepo->isValidSiteId($siteId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->findOneBy(array('siteId' => $siteId));

        if ($data['status'] == 'D') {
            $site->setStatus('D');
        }
        if ($data['status'] == 'A') {
            $site->setStatus('A');
        }

        $entityManager->persist($site);
        $entityManager->flush();

        $data = $this->buildSiteApiResponse($site);

        return new JsonModel($data);
    }

    /**
     * create - Create or Clone a site @todo Be more selective with Exceptions
     *
     * @param array $data - see buildSiteApiResponse()
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

        try {
            $data = $this->prepareNewSiteData($data);
        } catch (\Exception $e) {

            return new ApiJsonModel(null, null, 0, $e->getMessage());
        }

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManager()->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = $siteRepo->createNewSite($data['siteId']);

        $newSite->populate($data);

        $author = $this->getCurrentUser()->getName();

        $this->createDefaultPages($newSite, $author);

        $entityManager = $this->getEntityManager();

        $entityManager->persist($newSite);

        $entityManager->flush();

        $siteApiResponse = $this->buildSiteApiResponse($newSite);

        return new ApiJsonModel($siteApiResponse, null, 1, 'Success');
    }

    /**
     * prepareRequestData
     *
     * @param $data
     *
     * @return mixed
     * @throws \Exception
     * @throws \Rcm\Repository\DomainNotFoundException
     */
    protected function prepareNewSiteData($data)
    {
        $entitymanager = $this->getEntityManager();

        // Site Id
        if (empty($data['siteId'])) {
            $data['siteId'] = null;
        }

        // Language
        if (empty($data['language'])) {
            throw new \Exception('Language is required to create new site.');
        }

        if (!empty($data['language']['iso639_2t'])) {

            /** @var \Rcm\Repository\Language $languageRepo */
            $languageRepo = $entitymanager->getRepository('\Rcm\Entity\Language');

            $data['language'] = $languageRepo->getLanguageByString(
                $data['language']['iso639_2t'],
                'iso639_2t'
            );
        } else {

            throw new \Exception(
                'Language format (iso639_2t) required to create new site.'
            );
        }

        if (!$data['language'] instanceof Language) {

            throw new \Exception('Language could not be found.');
        }

        // Country
        if (empty($data['country'])) {

            throw new \Exception('Country is required to create new site.');
        }

        if (!empty($data['country']['iso3'])) {

            /** @var \Rcm\Repository\Country $countryRepo */
            $countryRepo = $entitymanager->getRepository('\Rcm\Entity\Country');

            $data['country'] = $countryRepo->getCountryByString(
                $data['country']['iso3'],
                'iso3'
            );
        } else {

            throw new \Exception(
                'Country format (iso3) required to create new site.'
            );
        }

        if (!$data['country'] instanceof Country) {

            throw new \Exception('Country could not be found.');
        }

        // Domain
        if (empty($data['domain'])) {

            throw new \Exception('Domain is required to create new site.');
        }

        if (!empty($data['domain']['defaultLanguage'])
            && !empty($data['domain']['defaultLanguage']['iso639_2t'])
        ) {
            /** @var \Rcm\Repository\Language $languageRepo */
            $languageRepo = $entitymanager->getRepository('\Rcm\Entity\Language');

            $data['domain']['defaultLanguage'] = $languageRepo->getLanguageByString(
                $data['domain']['defaultLanguage']['iso639_2t'],
                'iso639_2t'
            );
        } else {
            $data['domain']['defaultLanguage'] = $data['language'];
        }

        if (!$data['domain']['defaultLanguage'] instanceof Language) {

            throw new \Exception('Domain default language could not be found.');
        }

        // Create Domain
        if (!empty($data['domain']['domain'])) {

            /** @var \Rcm\Repository\Domain $domainRepo */
            $domainRepo = $entitymanager->getRepository('\Rcm\Entity\Domain');

            $data['domain'] = $domainRepo->createDomain(
                $data['domain']['domain'],
                $data['domain']['defaultLanguage']
            );
        } else {

            throw new \Exception('Domain name is required to create new site.');
        }

        if (!$data['domain'] instanceof Domain) {

            throw new \Exception('Domain could not be created.');
        }

        return $data;
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
        foreach ($this->defaultPages as $key => $config) {

            $page = new Page();
            $page->setSite($site);
            $page->setName($key);
            $page->setDescription($config['decription']);
            $page->setPageTitle($config['pageTitle']);
            $page->setAuthor($author);

            $site->addPage($page);
        }
    }

    /**
     * buildSiteApiResponse
     *
     * @param Site $site
     *
     * @return SiteApiResponse
     */
    protected function buildSiteApiResponse(Site $site)
    {
        $siteApiResponse = new SiteApiResponse();

        $siteApiResponse->populateFromSite($site);

        return $siteApiResponse;
    }
}