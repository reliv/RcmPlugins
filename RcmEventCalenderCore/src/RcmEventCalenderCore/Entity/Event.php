<?php

namespace RcmEventCalenderCore\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_event_calender_event")
 */

class Event
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $eventId;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="events",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId")
     **/
    protected $event;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $firstDay;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastDay;

    /**
     * @ORM\Column(type="string")
     */
    protected $mapAddress;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="days",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId")
     **/
    protected $category;

    function __construct(){
        $this->days = new ArrayCollection();
    }

    function getDaysText($dateFormat="F d"){
        if(
            !is_a($this->lastDay,'\DateTime')
            || $this->firstDay==$this->lastDay
        ) {
            return $this->firstDay->format($dateFormat);
        }else{
            return $this->firstDay->format($dateFormat)
                . ' - ' . $this->lastDay->format($dateFormat);
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setDays($days)
    {
        $this->days = $days;
    }

    public function getDays()
    {
        return $this->days;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the EventId property
     *
     * @param int $eventId
     *
     * @return null
     *
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Gets the EventId property
     *
     * @return int EventId
     *
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    public function setMapAddress($mapAddress)
    {
        $this->mapAddress = $mapAddress;
    }

    public function getMapAddress()
    {
        return $this->mapAddress;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setFirstDay($firstDay)
    {
        $this->firstDay = $firstDay;
    }

    public function getFirstDay()
    {
        return $this->firstDay;
    }

    public function setLastDay($lastDay)
    {
        $this->lastDay = $lastDay;
    }

    public function getLastDay()
    {
        return $this->lastDay;
    }
}
