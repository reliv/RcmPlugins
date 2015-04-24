<?php

namespace RcmEventCalenderCore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_event_calender_category")
 */
class Category
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $categoryId;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Event",
     *     mappedBy="category",
     *     indexBy="date",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $events;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * PHP calls this during json_encode()
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'categoryId' => $this->categoryId,
            'name' => $this->name,
        ];
    }

    /**
     * Sets the CategoryId property
     *
     * @param int $categoryId
     *
     * @return null
     *
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Gets the CategoryId property
     *
     * @return int CategoryId
     *
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setEvents($events)
    {
        $this->events = $events;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
