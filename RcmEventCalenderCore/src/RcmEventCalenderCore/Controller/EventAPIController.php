<?php

namespace RcmEventCalenderCore\Controller;

use \Zend\Mvc\Controller\AbstractRestfulController,
\Zend\View\Model\JsonModel;

class EventAPIController extends AbstractRestfulController
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

    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    function get($id){
        $event = $this->calender->getEvent($id);
        if(!$event){
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }
        return new JsonModel($event->jsonSerialize());
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    function create($data){
        $post=$this->getEvent()->getRequest()->getPost();
        $response= $this->getResponse();
        try{
            $eventId=$this->calender->createEvent(
                $post->get('categoryId'),
                $post->get('title'),
                $post->get('text'),
                $post->get('startDate'),
                $post->get('endDate'),
                $post->get('mapAddress')
            );
        }catch(\RcmEventCalenderCore\Exception\InvalidArgumentException $e){
            $response->setStatusCode(400);//Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(array('message'=>$e->getMessage()));
        }
        $location=$this->url()->fromRoute('rcm-event-calender-core-event')
            . "/$eventId";
        $response->setStatusCode(201);//Created
        $response->getHeaders()->addHeaderLine("Location: $location");
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

    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    function delete($id){
        $event = $this->calender->getEvent($id);
        if(!$event){
            $this->getResponse()->setStatusCode(404);
            return null;
        }
        $this->calender->deleteEvent($id);
        $this->getResponse()->setStatusCode(204);//204 = OK, But No Content
        return new JsonModel();
    }
}
