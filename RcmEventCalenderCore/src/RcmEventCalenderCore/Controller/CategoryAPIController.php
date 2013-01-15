<?php

namespace RcmEventCalenderCore\Controller;

use \Zend\Mvc\Controller\AbstractRestfulController,
\Zend\View\Model\JsonModel;

class CategoryAPIController extends AbstractRestfulController
{

    /**
     * @var \RcmEventCalenderCore\Model\Calender $calender
     */
    protected $calender;

    function __construct(
        \RcmEventCalenderCore\Model\Calender $calender
    ) {
        $this->calender = $calender;
    }

    /**
     * Return list of resources
     *
     * @return mixed
     */
    function getList(){
        $categories = $this->calender->getAllCategories();
        $categoryList = array();
        foreach($categories as $category){
            $categoryList[]= $category->jsonSerialize();
        }
        return new JsonModel($categoryList);
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    function get($id){
        $this->getResponse()->setStatusCode(403);//Forbidden
        return new JsonModel();
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    function create($data){
        $this->getResponse()->setStatusCode(403);//Forbidden
        return new JsonModel();
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    function update($id, $data){
        $this->getResponse()->setStatusCode(403);//Forbidden
        return new JsonModel();
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    function delete($id){
        $this->getResponse()->setStatusCode(403);//Forbidden
        return new JsonModel();
    }
}
