<?php

namespace RcmAdmin\Controller;

use RcmUser\Acl\Entity\AclRule;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Rcm\Http\Response;

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
     * Update an existing resource
     *
     * @param  string $id   $pageName
     * @param  array  $data $roles
     *
     * @return mixed
     */
    public function update($id, $data)
    {
        $roles = $data;

        $this->siteId = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        )->getCurrentSiteId();

        $this->aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        $this->resourceProvider = $this->getServiceLocator()->get(
            'Rcm\Acl\ResourceProvider'
        );

        $pageName = $id;

        //ACCESS CHECK
        if (!$this->rcmUserIsAllowed('page-permissions','edit', 'RcmAdmin'))
        {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return;

        }

        $resourceId = 'sites.' . $this->siteId . '.pages.' . $pageName;

        if(!$this->isValidResourceId($resourceId)){
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return;
        }

        $this->deletePermissions($resourceId);

        $this->addPermissions($roles, $resourceId);

        return new JsonModel(array($resourceId));

       // @TODO

    }

    public function deletePermissions($resourceId)
    {
        $rules = $this->aclDataService->getRulesByResource($resourceId)->getData();
        /** @var \RcmUser\Acl\Entity\AclRole $role */
        foreach ($rules as $rule) {

            $result = $this->aclDataService->deleteRule($rule);

            //@TODO if(!r$result->isSuccess() then ????)
        }
    }

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

    public function addPermission($roleId, $resourceId)
    {

        $this->aclDataService->createRule(
            $this->getAclRule($roleId, $resourceId)
        );
    }

    protected function getAclRule($roleId, $resourceId, $allowDeny = 'allow')
    {
        $rule = new AclRule();
        $rule->setRoleId($roleId);
        $rule->setRule($allowDeny);
        $rule->setResourceId($resourceId);
        $rule->setPrivilege('read');

        return $rule;
    }

    public function isValidResourceId($resourceId)
    {

        $resource = $this->resourceProvider->getResource($resourceId);

        var_dump($resource);

        return true;
    }
}