<?php

namespace RcmEventCalenderCore\Model;

class Calender
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    /**
     * @param \Doctrine\ORM\EntityManager $entityMgr
     */
    function __construct(\Doctrine\ORM\EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;
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
                order by e.firstDay
            ');
        $query->setParameter('categoryName', $category);
        return $query->getResult();
    }
}
