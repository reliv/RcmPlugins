<?php

namespace RcmEventCalenderCore\Controller;

use Zend\View\Model\JsonModel;

class CategoryAPIController extends AbstractAPIController
{

    /**
     * Return list of resources
     *
     * @return mixed
     */
    function getList()
    {

        $this->exitIfNotAdmin();

        $categories = $this->calender->getCategories();
        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[] = $category->jsonSerialize();
        }
        return new JsonModel($categoryList);
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     *
     * @return mixed
     */
    function get($id)
    {

        $this->exitIfNotAdmin();

        $category = $this->calender->getCategory($id);
        if (!$category) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }
        return new JsonModel($category->jsonSerialize());
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     *
     * @return mixed
     */
    function create($data)
    {

        $this->exitIfNotAdmin();

        //Ensure they posted all required fields to avoid undefined index errors
        $requiredErrorView = $this->checkRequired($data);
        if ($requiredErrorView) {
            return $requiredErrorView;
        }

        try {
            $categoryId = $this->calender->createCategory($data['name']);
        } catch (\RcmEventCalenderCore\Exception\InvalidArgumentException $e) {
            $this->getResponse()->setStatusCode(400); //Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(['message' => $e->getMessage()]);
        }
        $location = $this->getCategoriesUrl() . "/$categoryId";
        $this->getResponse()->setStatusCode(201); //Created
        $this->getResponse()->getHeaders()->addHeaderLine(
            "Location: $location"
        );
        return new JsonModel();
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     *
     * @return mixed
     */
    function update($id, $data)
    {

        $this->exitIfNotAdmin();

        //This can be implemented later to allow category renaming
        $this->getResponse()->setStatusCode(403); //Forbidden
        return new JsonModel();
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     *
     * @return mixed
     */
    function delete($id)
    {

        $this->exitIfNotAdmin();

        $event = $this->calender->getCategory($id);
        if (!$event) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }
        $this->calender->deleteCategory($id);
        return new JsonModel([]);
    }

    function checkRequired($data)
    {
        if (empty($data['name'])) {
            $this->getResponse()->setStatusCode(400); //Bad Request
            return new JsonModel(
                [
                    'message' => "Name is required"
                ]
            );
        }
        return null;
    }
}
