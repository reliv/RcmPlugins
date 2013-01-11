<?php

namespace RcmEventCalenderCore\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection
;

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
    protected $startDay;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endDay;

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

    /**
     * PHP calls this during json_encode()
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'eventId' => $this->eventId,
            'title'=> $this->title,
            'text' => $this->text,
            'startDay' => $this->startDay->format('Y-m-d'),
            'endDay' => $this->endDay->format('Y-m-d'),
        );
    }

    function getDaysText($dateFormat="F d"){
        if(
            $this->startDay==$this->endDay
        ) {
            return $this->startDay->format($dateFormat);
        }else{
            return $this->startDay->format($dateFormat)
                . ' - ' . $this->endDay->format($dateFormat);
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

    public function setStartDay($startDay)
    {
        $this->startDay = $startDay;
    }

    public function getFirstDay()
    {
        return $this->startDay;
    }

    public function setEndDay($endDay)
    {
        $this->endDay = $endDay;
    }

    public function getLastDay()
    {
        return $this->endDay;
    }
}
