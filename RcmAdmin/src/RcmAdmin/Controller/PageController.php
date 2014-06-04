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
use Rcm\Service\PageManager;
use RcmAdmin\Form\NewPageForm;
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
    /** @var \Rcm\Service\PageManager */
    protected $pageManager;

    /** @var \Zend\View\Model\ViewModel  */
    protected $view;

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     */
    public function __construct(
        PageManager $pageManager,
        AclDataService $aclDataService
    ) {
        $this->pageManager = $pageManager;
        $this->view        = new ViewModel();
        $this->view->setTerminal(true);

        $aclDataService->getAclData();
    }

    /**
     * Creates a new CMS page
     *
     * @return ViewModel
     */
    public function newAction()
    {

        $form = new NewPageForm($this->pageManager);

        $data = $this->request->getPost();

        $form->setData($data);

        if ($this->request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();
        }

        $this->view->setVariable('form', $form);
        return $this->view;
    }
}
