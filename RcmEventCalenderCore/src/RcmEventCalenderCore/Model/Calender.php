<?php

namespace RcmEventCalenderCore\Model;

use \RcmEventCalenderCore\Entity\Event,
    \RcmEventCalenderCore\Exception\InvalidArgumentException;

class Calender
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    protected $eventRepo;

    protected $categoryRepo;
    /**
     * @param \Doctrine\ORM\EntityManager $entityMgr
     */
    function __construct(\Doctrine\ORM\EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;

        $this->eventRepo = $this->entityMgr
            ->getRepository('RcmEventCalenderCore\Entity\Event');

        $this->categoryRepo  = $this->entityMgr
            ->getRepository('RcmEventCalenderCore\Entity\Category');
    }

    /**
     * @param integer $categoryId
     *
     * @return array
     */
    function getEvents($categoryId=null, $includeExpired=false){

        $join = null;
        $categoryClaus = null;
        if($categoryId){
            $join = 'JOIN e.category c';
            $categoryClaus = 'AND c.categoryId=:categoryId';
        }

        $nonExpiredClause = 'AND e.endDate >= CURRENT_DATE()';
        if($includeExpired){
            $nonExpiredClause = null;
        }

        $query = $this->entityMgr->createQuery(
            "
                SELECT e FROM RcmEventCalenderCore\Entity\Event e
                $join
                WHERE TRUE = TRUE
                $nonExpiredClause
                $categoryClaus
                ORDER BY e.startDate
            "
        );

        if($categoryId){
            $query->setParameter('categoryId', $categoryId);
        }
        return $query->getResult();
    }

    function getEvent($eventId){
        return $this->eventRepo->findOneByEventId($eventId);
    }

    function createEvent(
        $categoryId, $title, $text, $startDate, $endDate, $mapAddress
    ) {

        $category = $this->categoryRepo->findOneByCategoryId($categoryId);
        if(!$category){
            throw new InvalidArgumentException(
                "Category #$categoryId not found"
            );
        }

        $event = new Event();
        $event->setCategory($category);
        $event->setTitle($title);
        $event->setText($text);
        $event->setMapAddress($mapAddress);
        $event->setStartDateFromString($startDate);
        $event->setEndDateFromString($endDate);

        $this->entityMgr->persist($event);
        $this->entityMgr->flush($event);

        return $event->getEventId();
    }

    function updateEvent(
        $eventId, $categoryId, $title, $text, $startDate, $endDate, $mapAddress
    ) {

        $category = $this->categoryRepo->findOneByCategoryId($categoryId);
        if(!$category){
            throw new InvalidArgumentException(
                "Category #$categoryId not found"
            );
        }

        $event = $this->eventRepo->findOneByEventId($eventId);
        if(!$event){
            throw new InvalidArgumentException(
                "Event #$eventId not found"
            );
        }

        $event->setCategory($category);
        $event->setTitle($title);
        $event->setText($text);
        $event->setStartDateFromString($startDate);
        $event->setEndDateFromString($endDate);
        $event->setMapAddress($mapAddress);

        $this->entityMgr->flush($event);
    }

    function deleteEvent($eventId){
        $this->entityMgr->remove($this->eventRepo->findOneByEventId($eventId));
        $this->entityMgr->flush();
    }

    function getAllCategories(){
        return $this->categoryRepo->findAll();
    }
}
