<?php

namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use RcmUser\Acl\Entity\AclRule;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class PageViewPermissionsController extends AbstractRestfulController
{
    protected $siteId;

    /**
     * @var \Rcm\Acl\ResourceProvider $resourceProvider
     */
    protected $resourceProvider;

    /**
     * @var \RcmUser\Acl\Service\AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * @var \Rcm\Service\PageManager
     */
    protected $pageManager;

    /**
     * Update an existing resource
     *
     * @param  string $id   $pageName
     * @param  array  $data $roles
     *
     * @return mixed
     */
    public function update($id, $data)
    {
        /*
        {"data":{
          "siteId":"1",
          "pageType": "n",
          "pageName": "my-profile",
          "roles": [
          ]
        }}
        */
//        $this->siteId = $this->getServiceLocator()->get(
//            'Rcm\Service\SiteManager'
//        )->getCurrentSiteId();

        $this->aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        $this->resourceProvider = $this->getServiceLocator()->get(
            'Rcm\Acl\ResourceProvider'
        );
        $this->pageManager = $this->getServiceLocator()->get(
            'Rcm\Service\PageManager'
        );

        $siteId = $data->siteId;

        $pageName = $data->pageName;

        $pageType = $data->pageType;

        $roles = $data->roles;

//        $roles = $data;
//        $pageName = $id;
//        $siteId = '1';
//        $pageType = 'n';

        //ACCESS CHECK
        if (!$this->rcmUserIsAllowed('page-permissions', 'edit', 'RcmAdmin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return;
        }

        //IS PAGE VALID?
        $validPage = $this->pageManager->isPageValid($pageName, $pageType);

        if (!$validPage) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return;
        }

        //CREATE RESOURCE ID
        $resourceId = 'sites.' . $siteId . '.pages.' . 'n' . '.' . $pageName;

        if (!$this->isValidResourceId($resourceId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return;
        }

        //DELETE ALL PERMISSIONS
        $this->deletePermissions($resourceId);


        $this->addPermissions($roles, $resourceId);

        return new JsonModel(array($resourceId));

        // @TODO

    }

    /**
     * deletePermissions
     *
     * @param $resourceId
     *
     * @return void
     */
    public function deletePermissions($resourceId)
    {
        $rules = $this->aclDataService->getRulesByResource($resourceId)
            ->getData();
        /** @var \RcmUser\Acl\Entity\AclRole $role */
        foreach ($rules as $rule) {

            $result = $this->aclDataService->deleteRule($rule);

            //@TODO if(!r$result->isSuccess() then ????)
        }
    }

    /**
     * addPermissions
     *
     * @param $roles
     * @param $resourceId
     *
     * @return void
     */
    public function addPermissions($roles, $resourceId)
    {
        if (empty($roles)) {
            return;
        }

        foreach ($roles as $role) {
            $roleId = $this->aclDataService->getRoleByRoleId($role);
            $this->addPermission($role, $roleId);
        }

        $this->addPermission('guest', $resourceId, 'deny');
    }

    /**
     * addPermission
     *
     * @param $roleId
     * @param $resourceId
     *
     * @return void
     */
    public function addPermission($roleId, $resourceId)
    {
        $this->aclDataService->createRule(
            $this->getAclRule($roleId, $resourceId)
        );
    }

    /**
     * getAclRule
     *
     * @param        $roleId
     * @param        $resourceId
     * @param string $allowDeny
     *
     * @return AclRule
     * @throws \RcmUser\Exception\RcmUserException
     */
    protected function getAclRule($roleId, $resourceId, $allowDeny = 'allow')
    {
        $rule = new AclRule();
        $rule->setRoleId($roleId);
        $rule->setRule($allowDeny);
        $rule->setResourceId($resourceId);
        $rule->setPrivilege('read');

        return $rule;
    }

    /**
     * isValidResourceId
     *
     * @param $resourceId
     *
     * @return bool
     */
    public function isValidResourceId($resourceId)
    {
        $resource = $this->resourceProvider->getResource($resourceId);

        return true;
    }
}