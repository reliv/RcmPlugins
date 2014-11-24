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
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use Rcm\View\Model\RcmJsonModel;
use RcmAdmin\Entity\SiteApiRequest;
use RcmAdmin\Entity\SiteApiResponse;
use RcmAdmin\Entity\SiteResponse;
use RcmAdmin\InputFilter\SiteInputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * ApiAdminManageSitesController
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
class ApiAdminManageSitesController extends AbstractRestfulController
{
    /**
     * getConfig
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->serviceLocator->get('config');
    }

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

        $sitesObjects = $siteRepo->findAll();

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $sites[] = $this->buildSiteApiResponse($site);
        }

        return new JsonModel($sites);
    }

    /**\
     * get
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        // get default site data - kinda hacky, but keeps us to one controller
        if ($id == -1) {

            $data = $this->getDefaultSiteSettings();

            $site = new Site();

            $site->populate($data);

            $result = $this->buildSiteApiResponse($site);

            return new ApiJsonModel($result, null, 0, 'Success');
        }

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManager()->getRepository('\Rcm\Entity\Site');

        try {
            $site = $siteRepo->find($id);
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null, null, 1, "Failed to find site by id ({$id})"
            );
        }

        $result = $this->buildSiteApiResponse($site);

        return new ApiJsonModel($result, null, 0, 'Success');
    }

    /**
     * update @todo - allow update of all properties
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
     * create - Create or Clone a site
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

        $inputFilter = new SiteInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                array(),
                null,
                1,
                'Some values are missing or invalid.',
                $inputFilter->getMessages()
            );
        }

        try {

            $data = $this->prepareNewSiteData($data);

            /** @var \Rcm\Repository\Domain $domainRepo */
            $domainRepo = $this->getEntityManager()->getRepository(
                '\Rcm\Entity\Domain'
            );

            $data['domain'] = $domainRepo->createDomain($data['domain']);

        } catch (\Exception $e) {

            return new ApiJsonModel(null, null, 1, $e->getMessage());
        }

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = $siteRepo->createNewSite($data['siteId']);

        $newSite->populate($data);

        $author = $this->getCurrentUser()->getName();

        $this->createPages($newSite, $author, $this->getDefaultSitePageSettings());



        try {
            $entityManager->persist($newSite);

            $entityManager->flush();
        } catch (\Exception $e) {

            return new ApiJsonModel(null, null, 1, $e->getMessage());
        }

        $this->createPagePlugins($newSite, $this->getDefaultSitePageSettings());

        try {
            $entityManager->flush();
        } catch (\Exception $e) {

            return new ApiJsonModel(null, null, 1, $e->getMessage());
        }

        $siteApiResponse = $this->buildSiteApiResponse($newSite);

        return new ApiJsonModel($siteApiResponse, null, 0, 'Success');
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

        $siteApiResponse->populateFromObject($site);

        return $siteApiResponse;
    }

    ////////////////////

    /**
     * Prepare Request Data
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     * @throws \Rcm\Repository\DomainNotFoundException
     */
    protected function prepareNewSiteData($data)
    {
        $data = $this->prepareDefaultValues($data);

        $entitymanager = $this->getEntityManager();

        // Site Id
        if (empty($data['siteId'])) {
            $data['siteId'] = null;
        }

        // Language
        if (empty($data['language'])) {
            throw new \Exception('Language is required to create new site.');
        }

        if (!empty($data['language'])) {

            /** @var \Rcm\Repository\Language $languageRepo */
            $languageRepo = $entitymanager->getRepository('\Rcm\Entity\Language');

            $data['language'] = $languageRepo->getLanguageByString(
                $data['language'],
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

        if (!empty($data['country'])) {

            /** @var \Rcm\Repository\Country $countryRepo */
            $countryRepo = $entitymanager->getRepository('\Rcm\Entity\Country');

            $data['country'] = $countryRepo->getCountryByString(
                $data['country'],
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

        return $data;
    }


    /**
     * prepareDefaultValues
     *
     * @param array $data
     *
     * @return array
     */
    protected function prepareDefaultValues($data)
    {
        $defaults = $this->getDefaultSiteSettings();

        foreach ($defaults as $key => $value) {

            if (empty($data[$key])) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * getDefaultSiteSettings
     *
     * @return array
     */
    public function getDefaultSiteSettings()
    {
        $config = $this->getConfig();

        $myConfig = $config['rcmAdmin'];

        if (!empty($myConfig['defaultSiteSettings'])
            && is_array(
                $myConfig['defaultSiteSettings']
            )
        ) {
            return $myConfig['defaultSiteSettings'];
        }

        return array();
    }

    /**
     * getDefaultSiteSettings
     *
     * @return array
     */
    public function getDefaultSitePageSettings()
    {
        $myConfig = $this->getDefaultSiteSettings();

        $pagesData = array();

        if (!empty($myConfig['pages'])
            && is_array(
                $myConfig['pages']
            )
        ) {
            $pagesData = $myConfig['pages'];
        }

        return $pagesData;
    }

    //// REPOS //////////////////////////////////////////////////////////////

    /**
     * createPages
     *
     * @param Site  $site
     * @param string $author
     * @param array $pagesData
     *
     * @return void
     */
    public function createPages(Site $site, $author, $pagesData = array())
    {
        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $this->getEntityManager()->getRepository('\Rcm\Entity\Page');

        foreach ($pagesData as $name => $pageData) {

            $page = $pageRepo->createNewPage(
                $pageData['name'],
                $pageData['pageTitle'],
                $pageData['pageLayout'],
                $author,
                $site,
                $pageData['pageType'],
                true,
                true
            );
            $page->setDescription($pageData['description']);
        }
    }

    /**
     * createPagePlugins
     *
     * @param Site  $site
     * @param array $pagesData
     *
     * @return void
     */
    protected function createPagePlugins(Site $site, $pagesData = array())
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $entityManager->getRepository('\Rcm\Entity\PluginInstance');

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $entityManager->getRepository('\Rcm\Entity\PluginWrapper');

        foreach ($pagesData as $pageName => $pageData) {

            if(empty($pageData['plugins'])){
                continue;
            }

            $page = $pageRepo->getPageByName($site, $pageData['name']);

            if(!empty($page)){

                $pageRevison = $page->getPublishedRevision();

                foreach($pageData['plugins'] as $name => $data){

                    $pluginData = $this->preparePluginData($name, $data);

                    $pluginInstance = $pluginInstanceRepo->saveNewInstance(
                        $pluginData['plugin'],
                        $pluginData['saveData'],
                        $pluginData['siteWide'],
                        $pluginData['displayName']
                    );

                    $pluginData['instanceId'] = $pluginInstance->getInstanceId();

                    $pluginData = $this->preparePluginWrapperData($pluginData);

                    $wrapper = $pluginWrapperRepo->savePluginWrapper($pluginData, $page->getPageLayout());

                    $pageRevison->addPluginWrapper($wrapper);

                    $entityManager->persist($pageRevison);
                }
            }
        }

        //$entityManager->flush();
    }

    /**
     * preparePagesData
     *
     * @param $pagesData
     *
     * @return mixed
     */
    protected function preparePagesData($pagesData)
    {
        foreach($pagesData as $key => $value){

//            if(empty($pagesData[$key]['name'])){
//                $pagesData[$key]['name'] = $key;
//            }
//            if(empty($pagesData[$key]['pageTitle'])){
//                $pagesData[$key]['pageTitle'] = 'My Page';
//            }
//            if(empty($pagesData[$key]['description'])){
//                $pagesData[$key]['description'] = 'My Page';
//            }
//            if(empty($pagesData[$key]['pageLayout'])){
//                $pagesData[$key]['pageLayout'] = 'default';
//            }
//            if(empty($pagesData[$key]['pageType'])){
//                $pagesData[$key]['pageType'] = 'n';
//            }
        }

        return $pagesData;
    }

    /**
     * preparePluginArray
     *
     * @param string $pluginName
     * @param array $pluginData
     *
     * @return array
     */
    public function preparePluginData($pluginName, $pluginData = array())
    {
        // backwards compatible
        if(!empty($pluginData['sitewideName']) && empty($pluginData['displayName'])){
            $pluginData['displayName'] = $pluginData['sitewideName'];
        }

        if(empty($pluginData['displayName'])) {
            $pluginData['displayName'] = $pluginName;
        }

        $pluginData['sitewideName'] = $pluginData['displayName'];

        // backwards compatible
        if(!empty($pluginData['name']) && empty($pluginData['plugin'])){
            $pluginData['plugin'] = $pluginData['name'];
        }

        if(empty($pluginData['plugin'])){
            $pluginData['plugin'] = $pluginName;
        }

        $pluginData['name'] = $pluginData['plugin'];

        if(empty($pluginData['saveData'])){
            $pluginData['saveData'] = array();
        }

        // backwards compatible
        if(!empty($pluginData['isSitewide']) && empty($pluginData['siteWide'])){
            $pluginData['siteWide'] = $pluginData['isSitewide'];
        }

        if(empty($pluginData['siteWide'])){
            $pluginData['siteWide'] = 0;
        }

        $pluginData['isSitewide'] = $pluginData['siteWide'];

        if(empty($pluginData['instanceConfig'])){
            $pluginData['instanceConfig'] = array();
        }

        return $pluginData;
    }

    /**
     * preparePluginWrapperData
     *
     * @param array $pluginData
     * @param null  $pageContainer
     *
     * @return array
     */
    public function preparePluginWrapperData($pluginData = array(), $pageContainer = null)
    {

        if(empty($pluginData['rank'])){
            $pluginData['rank'] = 0;
        }

        // backwards compatible
        if(!empty($pluginData['float'])){
            $pluginData['divFloat'] = $pluginData['float'];
        }

        if(empty($pluginData['divFloat'] )){
            $pluginData['divFloat']  = null;
        }

        $pluginData['float'] = $pluginData['divFloat'];

        if(empty($pluginData['height'])){
            $pluginData['height'] = null;
        }

        if(empty($pluginData['width'])){
            $pluginData['width'] = null;
        }

        // backwards compatible
        if(!empty($pluginData['containerName']) && empty($pluginData['layoutContainer'])){
            $pluginData['layoutContainer'] = $pluginData['containerName'];
        }

        if(!empty($pageContainer) && empty($pluginData['layoutContainer'])){
            $pluginData['layoutContainer'] = $pageContainer;
        }

        if(empty($pluginData['layoutContainer'])){
            $pluginData['layoutContainer'] = 'default';
        }

        $pluginData['containerName'] = $pluginData['layoutContainer'];

        return $pluginData;
    }




}