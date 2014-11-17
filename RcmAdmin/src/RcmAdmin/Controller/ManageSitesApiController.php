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

use Rcm\Entity\Site;
use Rcm\Exception\CountryNotFoundException;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\LanguageNotFoundException;
use Rcm\Http\Response;
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
        $entityManager = $this->getEntityManger();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        $sitesObjects = $siteRepo->getSites(false);

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $sites[] = $this->getSiteResponse($site);
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
     * create - Create or Clone a site @todo Be more selective with Exceptions
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

        $siteRequest = new SiteResponse();
        $siteRequest->populate($data);

        /** @var \RcmAdmin\Model\SiteModel $siteModel */
        $siteModel = $this->getSiteModel();

        $view = new JsonModel();

        // Get Site
        try {
            $newSite = $this->buildSiteFromRequest($siteRequest);
        } catch (\Exception $e) {

            $siteRequest->setCode(0);
            $siteRequest->setMessage($e->getMessage());
            $view->setVariables($siteRequest);
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $view;
        }

        try {
        // Build new domain from request
        $domain = $siteModel->createDomain(
            $siteRequest->getDomain(),
            $newSite->getLanguage()
        );
        } catch (\Exception $e) {

            $siteResponse = $this->getSiteResponse($newSite, 0, $e->getMessage());
            $view->setVariables($siteResponse);
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $view;
        }

        $newSite->setDomain($domain);

        // Create pages
        try {
            $siteModel->createDefaultPages(
                $newSite,
                $this->getCurrentUser()->getName()
            );

            // Save
            $newSite = $siteModel->saveSite($newSite);
        } catch (\Exception $e) {

            $siteResponse = $this->getSiteResponse($newSite, 0, $e->getMessage());
            $view->setVariables($siteResponse);
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $view;
        }

        $siteResponse = $this->getSiteResponse($newSite, 1, 'Success');

        return new JsonModel($siteResponse);
    }

    protected function createNewSite(SiteResponse $siteRequest)
    {

            // new site
            /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site();


    }

    protected function cloneSite(SiteResponse $siteRequest)
    {

    }

    /**
     * getSiteResponse
     *
     * @param Site $site
     * @param int $code
     * @param string $message
     *
     * @return SiteResponse
     */
    protected function getSiteResponse(Site $site, $code = 1, $message = '')
    {
        $siteResponse = new SiteResponse();
        $siteResponse->populateFromSite($site);
        $siteResponse->setCode($code);
        $siteResponse->setMessage($message);
        return $siteResponse;
    }

    /**
     * buildSiteFromRequest
     *
     * @param SiteResponse $siteRequest
     *
     * @return Site
     * @throws \RcmAdmin\Model\SiteNotFoundException
     */
    public function buildSiteFromRequest(SiteResponse $siteRequest)
    {
        $siteModel = $this->getSiteModel();
        $site = $siteModel->getNewSite($siteRequest->getSiteId());

        if (!empty($siteRequest->getTheme())) {
            $site->setTheme($siteRequest->getTheme());
        }
        if (!empty($siteRequest->getSiteLayout())) {
            $site->setSiteLayout($siteRequest->getSiteLayout());
        }
        if (!empty($siteRequest->getSiteTitle())) {
            $site->setSiteTitle($siteRequest->getSiteTitle());
        }
        if (!empty($siteRequest->getStatus())) {
            $site->setStatus($siteRequest->getStatus());
        }
        if (!empty($siteRequest->getFavIcon())) {
            $site->setFavIcon($siteRequest->getFavIcon());
        }
        if (!empty($siteRequest->getLoginPage())) {
            $site->setLoginPage($siteRequest->getLoginPage());
        }
        if (!empty($siteRequest->getNotAuthorizedPage())) {
            $site->setNotAuthorizedPage($siteRequest->getNotAuthorizedPage());
        }

        // Get Language
        //$site->setLanguage($siteRequest->getLanguage());
        $language = $siteModel->getLanguage(
            $siteRequest->getLanguage(),
            $site->getLanguage()
        );

        if (empty($language)) {
            throw new LanguageNotFoundException('Language not found.');
        }

        // Get Country
        // $site->setCountry($siteRequest->getCountry());
        $country = $siteModel->getCountry(
            $siteRequest->getCountry(),
            $site->getCountry()
        );

        if (empty($country)) {
            throw new CountryNotFoundException('Country not found.');
        }

        // Setup site
        $site->setLanguage($language);
        $site->setCountry($country);

        return $site;
    }
}