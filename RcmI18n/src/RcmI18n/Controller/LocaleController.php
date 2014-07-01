<?php
 /**
 * AdminTranslationApiController.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Entity\Controller
 * @author    authorFirstAndLast <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18n\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * AdminTranslationApiController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Entity\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class LocaleController extends AbstractRestfulController
{

    public function getList()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $sites = $em->getRepository('\Rcm\Entity\Site')->findAll();

        $list = array();

        foreach ($sites as $site) {
          $siteId = $site->getSiteId();
          $site = $em->getRepository('\Rcm\Entity\Site')->findOneBy(
          array('siteId' => $siteId)
          );
          $country = $site->getCountry();
          $iso2 = $country->getIso2();

          $language = $site->getLanguage();
          $iso639 = $language->getIso6391();
          $list[] = $iso639 . '_' . $iso2;
        }

        $list = array_unique($list);
        $list = array_values($list);

        return new JsonModel($list);
    }


}
 