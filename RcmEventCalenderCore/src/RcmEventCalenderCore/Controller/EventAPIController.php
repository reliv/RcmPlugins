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
        $this->getResponse()->setStatusCode(403);//Forbidden
        return new JsonModel(
            array(
                'message' => 'Listing all resources is forbidden'
            )
        );
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
        $response= $this->getResponse();

        $event = $this->calender->getEvent($id);
        if(!$event){
            $response->setStatusCode(403);//Forbidden
            return new JsonModel(
                array(
                    'message' => 'PUT is forbidden for new resources. Use POST.'
                )
            );
        }

        //Pars the PUT vars (post doesn't work because this is a PUT)
        $put = array();
        parse_str($this->getEvent()->getRequest()->getContent(), $put);

        //Ensure they posted all required fields to avoid undefined index errors
        foreach(
            array(
                'categoryId',
                'title',
                'text',
                'startDate',
                'endDate',
                'mapAddress'
            )
            as $requiredName
        ) {
            if(empty($put[$requiredName])){
                $response->setStatusCode(400);//Bad Request
                return new JsonModel(
                    array(
                        'message'=> "Field $requiredName is required"
                    )
                );
            }
        }

        //Update the event
        try{
            $this->calender->updateEvent(
                $id,
                $put['categoryId'],
                $put['title'],
                $put['text'],
                $put['startDate'],
                $put['endDate'],
                $put['mapAddress']
            );
        }catch(\RcmEventCalenderCore\Exception\InvalidArgumentException $e){
            $response->setStatusCode(400);//Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(array('message'=>$e->getMessage()));
        }
        $this->getResponse()->setStatusCode(204);//204 = OK, No Content Returned
        return new JsonModel();
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
        $this->getResponse()->setStatusCode(204);//204 = OK, No Content Returned
        return new JsonModel();
    }
}
