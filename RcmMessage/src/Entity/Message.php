<?php

namespace RcmMessage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Message
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table (
 *     name="rcm_message_message"
 * )
 *
 */
class Message
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;
    /**
     * @var string $level
     * @ORM\Column(type="string", length=16, nullable=false)
     */
    public $level = 'default'; // ['default', 'info', 'warning', 'error', 'crit']
    /**
     * @var string $subject
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    public $subject = '';
    /**
     * @var string $message
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    public $message = '';
    /**
     * @var string $source
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    public $source = 'DEFAULT';
    /**
     * @var $destinations ArrayCollection destinations
     * @ORM\OneToMany(targetEntity="Destination", mappedBy="message", cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    public $destinations;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    public $dateCreated;

    /**
     * __construct
     */
    public function __construct()
    {

        $this->dateCreated = new \DataTime();
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
     * getLevel
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * setLevel
     *
     * @param $level
     *
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * getSubject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * setSubject
     *
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * setMessage
     *
     * @param $message
     *
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * getSource
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * setSource
     *
     * @param $source
     *
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * getDestinations
     *
     * @return array
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * setDestinations
     *
     * @param $destinations
     *
     * @return void
     */
    public function setDestinations($destinations)
    {
        $this->destinations = $destinations;
    }

    /**
     * getDateCreated
     *
     * @return \Datetime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * setDateCreated
     *
     * @param $dateCreated
     *
     * @return void
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }


}