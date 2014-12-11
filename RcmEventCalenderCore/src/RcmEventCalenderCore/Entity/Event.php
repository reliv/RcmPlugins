<?php

namespace RcmEventCalenderCore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use RcmEventCalenderCore\Exception\InvalidArgumentException;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_event_calender_event")
 */
class Event
{
    const DATE_FORMAT = 'm/d/Y';

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
     *     inversedBy="days"
     * )
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId")
     **/
    protected $category;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $endDate;

    /**
     * @ORM\Column(type="string")
     */
    protected $mapAddress;

    function __construct()
    {
        $this->days = new ArrayCollection();
    }

    /**
     * PHP calls this during json_encode()
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'eventId' => $this->eventId,
            'categoryId' => $this->category->getCategoryId(),
            'title' => $this->title,
            'text' => $this->text,
            'mapAddress' => $this->mapAddress,
            'startDate' => $this->startDate->format(self::DATE_FORMAT),
            'endDate' => $this->endDate->format(self::DATE_FORMAT),
        ];
    }

    /**
     * @param string $dateFormat see syntax for php's strftime() function
     *
     * @return string
     */
    function getDaysText($dateFormat = "%B %d")
    {
        //strftime must be used for non-english support
        if (
            $this->startDate == $this->endDate
        ) {
            return strftime($dateFormat, $this->startDate->getTimestamp());
        } else {
            return strftime($dateFormat, $this->startDate->getTimestamp())
            . ' - ' . strftime($dateFormat, $this->endDate->getTimestamp());
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
        if (!$mapAddress) {
            throw new InvalidArgumentException('Invalid mapAddress');
        }
        $this->mapAddress = $mapAddress;
    }

    public function getMapAddress()
    {
        return $this->mapAddress;
    }

    public function setText($text)
    {
        if (!$text) {
            throw new InvalidArgumentException('Invalid text');
        }
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTitle($title)
    {
        if (!$title) {
            throw new InvalidArgumentException('Invalid title');
        }
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function setStartDateFromString($date)
    {
        $dateTime = \DateTime::CreateFromFormat(self::DATE_FORMAT, $date);
        if (!$dateTime) {
            throw new InvalidArgumentException('Invalid startDate');
        }
        $this->setStartDate($dateTime);
    }

    public function setEndDateFromString($date)
    {
        $dateTime = \DateTime::CreateFromFormat(self::DATE_FORMAT, $date);
        if (!$dateTime) {
            throw new InvalidArgumentException('Invalid endDate');
        }
        $this->setEndDate($dateTime);
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    function getStartDateAsString()
    {
        if (is_a($this->startDate, '\DateTime')) {
            return $this->startDate->format(self::DATE_FORMAT);
        }
    }

    function getEndDateAsString()
    {
        if (is_a($this->endDate, '\DateTime')) {
            return $this->endDate->format(self::DATE_FORMAT);
        }
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    public function getLastDay()
    {
        return $this->endDate;
    }
}
