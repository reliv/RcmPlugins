<?php
namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use Rcm\Repository\Page;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use RcmAdmin\Form\NewPageForm;
use RcmAdmin\Form\PageForm;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Service\UserDataService;
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

    protected $siteId;


    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     * @param PageForm    $pageForm    Rcm Admin Page Form
     * @param integer     $siteId      RcmUser Acl Data Service
     */
    public function __construct(
        PageManager $pageManager,
        PageForm    $pageForm,
        $siteId
    ) {
        $this->pageManager = $pageManager;
        $this->pageForm    = $pageForm;
        $this->siteId      = $siteId;
        $this->view        = new ViewModel();

        $this->view->setTerminal(true);
    }

    /**
     * Creates a new CMS page
     *
     * @return ViewModel
     */
    public function newAction()
    {

        if (!$this->rcmUserIsAllowed('sites.'.$this->siteId.'.pages')) {
            $response =  new Response();
            $response->setStatusCode('401');

            return $response;
        }

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
        return $this->view;
    }
}
