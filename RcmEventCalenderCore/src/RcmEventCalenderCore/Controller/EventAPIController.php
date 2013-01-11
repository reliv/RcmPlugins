<?php

namespace RcmEventCalenderCore\Controller;

use \Zend\Mvc\Controller\AbstractActionController;

class EventAPIController extends AbstractActionController
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

    function eventGetAction(){
        $event = $this->calender->getEvent($this->getEventIdFromUrl());
        if(!$event){
            $this->exitNotFound();
        }
        $this->exitJson(json_encode($event->jsonSerialize()));

    }

    function eventDeleteAction(){
        $this->calender->deleteEvent($this->getEventIdFromUrl());
    }

    function getEventIdFromUrl(){
        return $this->getEvent()->getRouteMatch()->getParam('eventId');
    }

    function exitJson($json){
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 1 Jan 2001 01:00:00 GMT');
        header('Content-type: application/json');
        exit($json);
    }

    function exitNotFound(){
        header("HTTP/1.0 404 Not Found");
        exit('404 Not Found');
    }
}
