<?php
namespace RcmAdmin\Controller;

use Rcm\Repository\Page;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use RcmAdmin\Form\NewPageForm;
use RcmAdmin\Form\PageForm;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PageController extends AbstractActionController
{
    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \RcmAdmin\Form\PageForm  */
    protected $pageForm;

    /** @var \Zend\View\Model\ViewModel  */
    protected $view;

    /** @var \RcmUser\Acl\Service\AclDataService  */
    protected $aclDataService;


    /**
     * Constructor
     *
     * @param PageManager    $pageManager    Rcm Page Manager
     * @param PageForm       $pageForm       Rcm Admin Page Form
     * @param AclDataService $aclDataService RcmUser Acl Data Service
     */
    public function __construct(
        PageManager $pageManager,
        PageForm    $pageForm,
        AclDataService $aclDataService
    ) {
        $this->pageManager    = $pageManager;
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

        $form->setValidationGroup('url');
        $form->setData($data);

        if ($this->request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();

            // Create a new page
            if (empty($validatedData['page-template'])
                && !empty($validatedData['main-layout'])
            ) {
                $this->pageManager->createNewPage(
                    $validatedData['url'],
                    $validatedData['title'],
                    $validatedData['main-layout'],
                    'Westin Shafer'
                );
            }

            $send = array(
                'redirect' => $this->urlToPage($validatedData['url'], 'n')
            );

            return new JsonModel($send);

        } elseif ($this->request->isPost() && !$form->isValid()) {
            $this->view->setVariable('errors', $form->getMessages());
        }

        $this->view->setVariable('form', $form);
        $this->view->setVariable('aclRoles', $this->aclDataService->getAllRoles());
        return $this->view;
    }
}
