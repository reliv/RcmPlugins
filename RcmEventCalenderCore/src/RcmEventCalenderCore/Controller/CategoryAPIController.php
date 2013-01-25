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
        $categories = $this->calender->getCategories();
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
        $category = $this->calender->getCategory($id);
        if(!$category){
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }
        return new JsonModel($category->jsonSerialize());
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    function create($data){
        //Ensure they posted all required fields to avoid undefined index errors
        $requiredErrorView = $this->checkRequired($data);
        if($requiredErrorView){
            return $requiredErrorView;
        }

        try{
            $categoryId=$this->calender->createCategory($data['name']);
        }catch(\RcmEventCalenderCore\Exception\InvalidArgumentException $e){
            $this->getResponse()->setStatusCode(400);//Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(array('message'=>$e->getMessage()));
        }
        $location=$this->getCategoriesUrl(). "/$categoryId";
        $this->getResponse()->setStatusCode(201);//Created
        $this->getResponse()->getHeaders()->addHeaderLine("Location: $location");
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
        //This can be implemented later to allow category renaming
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
        $event = $this->calender->getCategory($id);
        if(!$event){
            $this->getResponse()->setStatusCode(404);
            return null;
        }
        $this->calender->deleteCategory($id);
        return new JsonModel(array());
    }

    function checkRequired($data){
        if(empty($data['name'])){
            $this->getResponse()->setStatusCode(400);//Bad Request
            return new JsonModel(
                array(
                    'message'=> "Field name is required"
                )
            );
        }
        return null;
    }

    function getCategoriesUrl(){
        return $this->url()->fromRoute('rcm-event-calender-core-category');
    }
}
