<?php

namespace RcmMessage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Destination
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmMessage\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table (
 *     name="rcm_message_destination"
 * )
 */
class Destination
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateViewed = null;

    /**
     * @var string $id
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $userId;

    /**
     * @var Message
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="destinations"))
     * @ORM\JoinColumn(name="messageId", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $message;

    /**
     * __construct
     *
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getDateViewed
     *
     * @return \DateTime
     */
    public function getDateViewed()
    {
        return $this->dateViewed;
    }

    /**
     * setDateViewed
     *
     * @param $dateViewed
     *
     * @return \DateTime
     */
    public function setDateViewed($dateViewed)
    {
        $this->dateViewed = $dateViewed;
    }

    /**
     * getUserId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * setUserId
     *
     * @param $userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
