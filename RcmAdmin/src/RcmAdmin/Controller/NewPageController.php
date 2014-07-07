<?php
/**
 * Admin Page Controller for the CMS
 *
 * This file contains the Admin Page Controller for the CMS.   This
 * should extend from the base class and should need no further modification.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use Rcm\Service\PageManager;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use RcmUser\User\Entity\User;

/**
 * Admin Page Controller for the CMS
 *
 * This is Admin Page Controller for the CMS.  This should extend from
 * the base class and should need no further modification.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method Response redirectToPage($pageName, $pageType) Redirect to CMS
 *                                                                  Page
 *
 * @method boolean rcmUserIsAllowed($resource, $action, $providerId) Is User Allowed
 * @method User rcmUserGetCurrentUser() Get Current User Object
 * @method string urlToPage($pageName, $pageType) Get Url To a Page
 */
class NewPageController extends AbstractActionController
{
    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \RcmAdmin\Form\NewPageForm  */
    protected $pageForm;

    /** @var \Zend\View\Model\ViewModel  */
    protected $view;

    protected $siteId;

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     * @param Form        $pageForm    Rcm Admin Page Form
     * @param integer     $siteId      RcmUser Acl Data Service
     */
    public function __construct(
        PageManager $pageManager,
        Form $pageForm,
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

        if (!$this->rcmUserIsAllowed(
            'sites.'.$this->siteId.'.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        )) {
            $response =  new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $form = $this->pageForm;

        /** @var \Zend\Http\Request $request */
        $request = $this->request;

        $data = $request->getPost();

        $form->setValidationGroup('url');
        $form->setData($data);

        if ($request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();

            // Create a new page
            if (empty($validatedData['page-template'])
                && !empty($validatedData['main-layout'])
            ) {
                $this->pageManager->createNewPage(
                    $validatedData['url'],
                    $validatedData['title'],
                    $validatedData['main-layout'],
                    $this->rcmUserGetCurrentUser()->getName()
                );
            }

            $send = array(
                'redirect' => $this->urlToPage($validatedData['url'], 'n')
            );

            return new JsonModel($send);

        } elseif ($request->isPost() && !$form->isValid()) {
            $this->view->setVariable('errors', $form->getMessages());
        }

        $this->view->setVariable('form', $form);
        return $this->view;
    }
}
