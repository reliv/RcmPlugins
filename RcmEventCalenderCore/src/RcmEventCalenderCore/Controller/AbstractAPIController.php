<?php

namespace RcmEventCalenderCore\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

abstract class AbstractAPIController extends AbstractRestfulController
{

    /**
     * @var \RcmEventCalenderCore\Model\Calender $calender
     */
    protected $calender;

    protected $adminLoggedIn;

    function __construct(
        \RcmEventCalenderCore\Model\Calender $calender,
        \Rcm\Model\UserManagement\UserManagerInterface $userMgr
    ) {
        $this->calender = $calender;
        $this->adminLoggedIn = is_a(
            $userMgr->getLoggedInAdminPermissions(),
            '\Rcm\Entity\AdminPermissions'
        );
    }

    function exitIfNotAdmin()
    {
        if (!$this->adminLoggedIn) {
            header('HTTP/1.0 401 Unauthorized');
            //Always return valid JSON to make jQuery work easier
            header('Content-type: application/json');
            die(json_encode(['message' => 'Must be admin.']));
        }
    }

    function getCategoriesUrl()
    {
        return $this->url()->fromRoute('rcm-event-calender-core-category');
    }

    function getEventsUrl()
    {
        return $this->url()->fromRoute('rcm-event-calender-core-event');
    }
}
