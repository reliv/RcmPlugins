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

use
    Rcm\Exception\InvalidArgumentException;
use
    Rcm\Exception\PageNotFoundException;
use
    Rcm\Http\Response;
use
    Rcm\Service\PageManager;
use
    RcmUser\User\Entity\User;
use
    Zend\Mvc\Controller\AbstractActionController;
use
    Zend\View\Model\JsonModel;
use
    Zend\View\Model\ViewModel;

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
 * @method string urlToPage($pageName, $pageType = 'n', $pageRevision = null) Get Url To a Page
 */
class PageController extends AbstractActionController
{
    /** @var \Rcm\Service\PageManager */
    protected $pageManager;

    /** @var \Zend\View\Model\ViewModel */
    protected $view;

    protected $siteId;

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     * @param integer     $siteId      RcmUser Acl Data Service
     */
    public function __construct(
        PageManager $pageManager,
        $siteId
    ) {
        $this->pageManager = $pageManager;
        $this->siteId = $siteId;
        $this->view = new ViewModel();

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
            'sites.' . $this->siteId . '.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        /** @var \RcmAdmin\Form\NewPageForm $form */
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('RcmAdmin\Form\NewPageForm');

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
            } elseif (!empty($validatedData['page-template'])) {
                $this->pageManager->copyPage(
                    $validatedData['page-template'],
                    $validatedData['url'],
                    $this->rcmUserGetCurrentUser()->getName(),
                    $validatedData['title']
                );
            }

            $this->view->setVariable(
                'newPageUrl',
                $this->urlToPage(
                    $validatedData['url'],
                    'n'
                )
            );
            $this->view->setTemplate('rcm-admin/page/success');
            return $this->view;

        } elseif ($request->isPost() && !$form->isValid()) {
            $this->view->setVariable(
                'errors',
                $form->getMessages()
            );
        }

        $this->view->setVariable(
            'form',
            $form
        );
        return $this->view;
    }

    /**
     * createPageFromTemplateAction
     *
     * @return Response|ViewModel
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function createPageFromTemplateAction()
    {
        if (!$this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $sourcePage = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $sourcePageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $sourcePageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );


        /** @var \RcmAdmin\Form\CreateTemplateFromPageForm $form */
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('RcmAdmin\Form\CreateTemplateFromPageForm');

        /** @var \Zend\Http\Request $request */
        $request = $this->request;

        $data = $request->getPost();

        $form->setValidationGroup('template-name');
        $form->setData($data);

        if ($request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();

            $page = $this->pageManager->getPageByName(
                $sourcePage,
                $sourcePageType
            );

            if (empty($page)) {
                throw new PageNotFoundException('Unable to locate source page to copy');
            }

            $pageId = $page->getPageId();

            $this->pageManager->copyPage(
                $pageId,
                $validatedData['template-name'],
                $this->rcmUserGetCurrentUser()->getName(),
                null,
                $sourcePageRevision,
                't'
            );

            $this->view->setVariable(
                'newPageUrl',
                $this->urlToPage(
                    $validatedData['template-name'],
                    't'
                )
            );
            $this->view->setTemplate('rcm-admin/page/success');
            return $this->view;
        }

        $this->view->setVariable(
            'form',
            $form
        );
        $this->view->setVariable(
            'rcmPageName',
            $sourcePage
        );
        $this->view->setVariable(
            'rcmPageRevision',
            $sourcePageRevision
        );
        $this->view->setVariable(
            'rcmPageType',
            $sourcePageType
        );
        return $this->view;
    }

    /**
     * publishPageRevisionAction
     *
     * @return Response|\Zend\Http\Response
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function publishPageRevisionAction()
    {
        if (!$this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $pageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        if (!is_numeric($pageRevision)) {
            throw new InvalidArgumentException(
                'Invalid Page Revision Id.'
            );
        }

        $this->pageManager->publishPageRevision($pageRevision);

        return $this->redirect()->toUrl(
            $this->urlToPage(
                $pageName,
                $pageType
            )
        );

        /* Ajax request.  Makes publish take Twice as long, and
         * fails silently when problems arise.  Recommended not
         * to use, but kept here to settle any disputes.
        */
//        $view = new JsonModel();
//        $view->setVariable('redirect', $this->urlToPage($pageName, $pageType));
//        return $view;
    }

    /**
     * savePageAction
     *
     * @return Response|\Zend\Http\Response
     */
    public function savePageAction()
    {
        if (!$this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages',
            'edit',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        // @todo - might validate these against the data coming in
        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                null
            );

        $pageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                null
            );

        $request = $this->getRequest();

        if ($request->isPost()) {

            $data = $request->getPost();

            // <TEMP_ERROR>
            $response = new Response();
            $response->setStatusCode('501');

            return $response;
            // </TEMP_ERROR>

            $result = $this->pageManager->savePage($data);

            return $this->getJsonResponse($result);
        }

        $response = new Response();
        $response->setStatusCode('404');

        return $response;
    }

    /**
     * getJsonResponse
     *
     * @param $data $data
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getJsonResponse($data)
    {
        $view = new JsonModel();
        $view->setTerminal(true);

        $response = $this->getResponse();
        $response->setContent(json_encode($data));

        return $response;
    }
}
