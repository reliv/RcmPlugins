<?php

namespace RcmEventCalenderCore\Model;

use \RcmEventCalenderCore\Entity\Event,
    \RcmEventCalenderCore\Entity\Category,
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
    function __construct(\Doctrine\ORM\EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;

        $this->eventRepo = $this->entityMgr
            ->getRepository('RcmEventCalenderCore\Entity\Event');

        $this->categoryRepo = $this->entityMgr
            ->getRepository('RcmEventCalenderCore\Entity\Category');
    }

    /**
     * @param null $categoryId
     * @param bool $includeExpired
     *
     * @return array
     */
    function getEvents($categoryId = null, $includeExpired = false)
    {

        $join = null;
        $categoryClaus = null;
        if ($categoryId) {
            $join = 'JOIN e.category c';
            $categoryClaus = 'AND c.categoryId=:categoryId';
        }

        $nonExpiredClause = 'AND e.endDate >= CURRENT_DATE()';
        if ($includeExpired) {
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

        if ($categoryId) {
            $query->setParameter('categoryId', $categoryId);
        }
        return $query->getResult();
    }

    /**
     * @param $eventId
     *
     * @return mixed
     */
    function getEvent($eventId)
    {
        return $this->eventRepo->findOneByEventId($eventId);
    }

    /**
     * @param $categoryId
     * @param $title
     * @param $text
     * @param $startDate
     * @param $endDate
     * @param $mapAddress
     *
     * @return int
     * @throws \RcmEventCalenderCore\Exception\InvalidArgumentException
     */
    function createEvent(
        $categoryId, $title, $text, $startDate, $endDate, $mapAddress
    )
    {

        $category = $this->categoryRepo->findOneByCategoryId($categoryId);
        if (!$category) {
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
        $this->entityMgr->flush();

        return $event->getEventId();
    }

    /**
     * @param $eventId
     * @param $categoryId
     * @param $title
     * @param $text
     * @param $startDate
     * @param $endDate
     * @param $mapAddress
     *
     * @throws \RcmEventCalenderCore\Exception\InvalidArgumentException
     */
    function updateEvent(
        $eventId, $categoryId, $title, $text, $startDate, $endDate, $mapAddress
    )
    {

        $category = $this->categoryRepo->findOneByCategoryId($categoryId);
        if (!$category) {
            throw new InvalidArgumentException(
                "Category #$categoryId not found"
            );
        }

        $event = $this->eventRepo->findOneByEventId($eventId);
        if (!$event) {
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

        $this->entityMgr->flush();
    }

    /**
     * @param $eventId
     */
    function deleteEvent($eventId)
    {
        $this->entityMgr->remove($this->eventRepo->findOneByEventId($eventId));
        $this->entityMgr->flush();
    }

    /**
     * @return array
     */
    function getCategories()
    {
        return $this->categoryRepo->findAll();
    }

    /**
     * @param $categoryId
     *
     * @return mixed
     */
    function getCategory($categoryId)
    {
        return $this->categoryRepo->findOneByCategoryId($categoryId);
    }

    /**
     * @param $name
     *
     * @return int
     */
    function createCategory($name)
    {

        $category = new Category();
        $category->setName($name);

        $this->entityMgr->persist($category);
        $this->entityMgr->flush();

        return $category->getCategoryId();
    }

    /**
     * @param $categoryId
     */
    function deleteCategory($categoryId)
    {
        $this->entityMgr->remove(
            $this->categoryRepo->findOneByCategoryId($categoryId)
        );
        $this->entityMgr->flush();
    }
}
