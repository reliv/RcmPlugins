<?php

namespace RcmEventCalender\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_event_calender_day")
 */

class Day
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $dayId;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Event",
     *     inversedBy="days",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(name="eventId", referencedColumnName="eventId")
     **/
    protected $event;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the DayId property
     *
     * @param int $dayId
     *
     * @return null
     *
     */
    public function setDayId($dayId)
    {
        $this->dayId = $dayId;
    }

    /**
     * Gets the DayId property
     *
     * @return int DayId
     *
     */
    public function getDayId()
    {
        return $this->dayId;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }
}
