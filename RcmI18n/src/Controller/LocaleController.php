<?php
/**
 * LocaleController.php
 *
 * Restful Controller for creating admin screens for translations
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmI18n\Entity\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmI18n\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class LocaleController extends AbstractRestfulController
{
    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function getList()
    {

        return new JsonModel(
            [
                'locales' =>
                    $this->getServiceLocator()
                        ->get('RcmI18n\Model\Locales')->getLocales()
                ,
                'currentSiteLocale' => $this->getServiceLocator()->get(
                        'Rcm\Service\CurrentSite'
                    )->getLocale()
            ]
        );
    }
}
