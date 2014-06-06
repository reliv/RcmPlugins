<?php
/**
 * Service Factory for the Admin Page Controller
 *
 * This file contains the factory needed to generate a Admin Page Controller.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmAdmin\Controller;

use Rcm\Repository\Page;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use RcmAdmin\Form\NewPageForm;
use RcmAdmin\Form\PageForm;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Service Factory for the Admin Page Controller
 *
 * Factory for the Admin Page Controller.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PageController extends AbstractActionController
{
    /** @var \RcmAdmin\Form\PageForm  */
    protected $pageForm;

    /** @var \Zend\View\Model\ViewModel  */
    protected $view;

    /** @var \RcmUser\Acl\Service\AclDataService  */
    protected $aclDataService;

    /**
     * Constructor
     *
     * @param PageForm       $pageForm       Rcm Admin Page Form
     * @param AclDataService $aclDataService RcmUser Acl Data Service
     */
    public function __construct(
        PageForm    $pageForm,
        AclDataService $aclDataService
    ) {
        $this->pageForm       = $pageForm;
        $this->aclDataService = $aclDataService;
        $this->view           = new ViewModel();

        $this->view->setTerminal(true);
    }

    /**
     * Creates a new CMS page
     *
     * @return ViewModel
     */
    public function newAction()
    {

        $form = $this->pageForm;

        $data = $this->request->getPost();

        $form->setData($data);

        if ($this->request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();
        }

        $this->view->setVariable('form', $form);
        $this->view->setVariable('aclRoles', $this->aclDataService->fetchAllRoles());
        return $this->view;
    }
}
