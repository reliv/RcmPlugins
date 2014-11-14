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
use Rcm\Entity\Site;
use Rcm\Http\Response;
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
        $entityManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');

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
        )) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        if (!is_array($data)) {
            throw new \Exception('Invalid data format');
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');

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

        $data = $this->getSiteArray($site);

        return new JsonModel($data);
    }

    /**
     * create - Create or Clone a site
     *
     * @param array $data - see getSiteArray()
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

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->find($data['siteId']);

        if ($site) {
            // clone
            $newSite = clone($site);

        } else {
            // new site
            $newSite = new Site();
        }

        if (!empty($data['country'])) {

            // @todo Get country entity and set it
        }

        if (!empty($data['language'])) {

            // @todo Get language entity and set it
        }

        if (!empty($data['domain'])) {
            /** @var \Rcm\Entity\Domain $newDomain */
            $newDomain = $entityManager->getRepository('\Rcm\Entity\Domain')->findOneBy(array('domain' => $data['domain']));

            if (empty($newDomain)) {

                // @todo Lets build the new domain if it doesnt exist?
                $newDomain = new Domain();
                $newDomain->setDomainName($data['domain']);
                $siteRepo->getDoctrine()->persist($newDomain);
                //throw new DomainNotFoundException('A new site requires a valid and defined domain.');
            }

            $newSite->setDomain($newDomain);
        }

        $entityManager->persist($newSite);

        $entityManager->flush();

        $data = $this->getSiteArray($newSite);

        return new JsonModel($data);
    }

    /**
     * getSiteArray
     *
     * @param Site $site
     *
     * @return array
     */
    protected function getSiteArray(Site $site)
    {

        $siteArr = array(
            "siteId" => $site->getSiteId(),
            "domain" => $site->getDomain()->getDomainName(),
            "theme" => $site->getTheme(),
            "siteLayout" => $site->getSiteLayout(),
            "siteTitle" => $site->getSiteTitle(),
            "language" => $site->getLanguage()->getIso6391(),
            "country" => $site->getCountry()->getIso3(),
            "status" => $site->getStatus(),
            "favIcon" => $site->getFavIcon(),
            "loginPage" => $site->getLoginPage(),
            "notAuthorizedPage" => $site->getNotAuthorizedPage(),
        );

        return $siteArr;
    }

}