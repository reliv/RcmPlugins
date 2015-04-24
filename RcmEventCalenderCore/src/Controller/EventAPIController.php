<?php

namespace RcmEventCalenderCore\Controller;

use Zend\View\Model\JsonModel;

class EventAPIController extends AbstractAPIController
{

    /**
     * Return list of resources
     *
     * @return mixed
     */
    function getList()
    {

        $this->exitIfNotAdmin();

        $categoryId = $this->params()->fromQuery('categoryId', null);
        $events = $this->calender->getEvents($categoryId);
        $eventList = [];
        foreach ($events as $event) {
            $eventList[] = $event->jsonSerialize();
        }
        return new JsonModel($eventList);
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

        $event = $this->calender->getEvent($id);
        if (!$event) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel();
        }
        return new JsonModel($event->jsonSerialize());
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
            $eventId = $this->calender->createEvent(
                $data['categoryId'],
                $data['title'],
                $data['text'],
                $data['startDate'],
                $data['endDate'],
                $data['mapAddress']
            );
        } catch (\RcmEventCalenderCore\Exception\InvalidArgumentException $e) {
            $this->getResponse()->setStatusCode(400); //Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(['message' => $e->getMessage()]);
        }
        $location = $this->getEventsUrl() . "/$eventId";
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

        //Forbid new Id's
        $event = $this->calender->getEvent($id);
        if (!$event) {
            $this->getResponse()->setStatusCode(403); //Forbidden
            return new JsonModel(
                [
                    'message' => 'PUT is forbidden for new resources. Use POST.'
                ]
            );
        }

        //Ensure they posted all required fields to avoid undefined index errors
        $requiredErrorView = $this->checkRequired($data);
        if ($requiredErrorView) {
            return $requiredErrorView;
        }

        //Update the event
        try {
            $this->calender->updateEvent(
                $id,
                $data['categoryId'],
                $data['title'],
                $data['text'],
                $data['startDate'],
                $data['endDate'],
                $data['mapAddress']
            );
        } catch (\RcmEventCalenderCore\Exception\InvalidArgumentException $e) {
            $this->getResponse()->setStatusCode(400); //Bad Request
            //Return the message so troubleshooters tell which field is invalid
            return new JsonModel(['message' => $e->getMessage()]);
        }
        return new JsonModel([]);
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

        $event = $this->calender->getEvent($id);
        if (!$event) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }
        $this->calender->deleteEvent($id);
        return new JsonModel([]);
    }

    function checkRequired($data)
    {
        foreach (
            [
                'categoryId',
                'title',
                'text',
                'startDate',
                'endDate',
                'mapAddress'
            ]
            as $requiredName
        ) {
            if (empty($data[$requiredName])) {
                $this->getResponse()->setStatusCode(400); //Bad Request
                return new JsonModel(
                    [
                        'message' => "Field $requiredName is required"
                    ]
                );
            }
        }
        return null;
    }
}
