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
     * @param $category
     *
     * @return array
     */
    function getEventsInCategory($category){
        $query = $this->entityMgr->createQuery(
            '
                SELECT e FROM RcmEventCalenderCore\Entity\Event e
                JOIN e.category c
                WHERE c.name=:categoryName
                order by e.startDate
            ');
        $query->setParameter('categoryName', $category);
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

    function deleteEvent($eventId){
        $this->entityMgr->remove($this->eventRepo->findOneByEventId($eventId));
        $this->entityMgr->flush();
    }
}
