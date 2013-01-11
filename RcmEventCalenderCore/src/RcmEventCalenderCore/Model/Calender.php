<?php

namespace RcmEventCalenderCore\Model;

class Calender
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    protected $eventRepo;

    /**
     * @param \Doctrine\ORM\EntityManager $entityMgr
     */
    function __construct(\Doctrine\ORM\EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;
        $this->eventRepo  = $this->entityMgr
            ->getRepository('RcmEventCalenderCore\Entity\Event');
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
                order by e.startDay
            ');
        $query->setParameter('categoryName', $category);
        return $query->getResult();
    }

    function getEvent($eventId){
        return $this->eventRepo->findOneByEventId($eventId);
    }

    function deleteEvent($eventId){
        $this->entityMgr->remove($this->eventRepo->findOneByEventId($eventId));
    }
}
