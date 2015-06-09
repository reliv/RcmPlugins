<?php

namespace RcmMessage\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Entity\ApiBase;
use Zend\Form\Element\DateTime;

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
 * @ORM\Entity (repositoryClass="RcmMessage\Repository\Message")
 * @ORM\Table (
 *     name="rcm_message_message"
 * )
 *
 */
class Message extends ApiBase
{
    const LEVEL_DEFAULT = 16;

    const LEVEL_CRITICAL = 2;
    const LEVEL_ERROR = 4;
    const LEVEL_WARNING = 8;
    const LEVEL_INFO = 16;
    const LEVEL_SUCCESS = 32;

    /**
     * @var int $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @var string $level
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $level = 2;
    /**
     * @var string $subject
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected $subject = '';
    /**
     * @var string $message
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $message = '';
    /**
     * @var string $source
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $source = null;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated = null;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * setLevel
     *
     * @param int $level
     *
     * @return void
     */
    public function setLevel($level)
    {
        if (empty($level)) {
            $level = self::LEVEL_DEFAULT;
        }

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
        if (empty($source)) {
            $source = null;
        }
        $this->source = $source;
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
     * @param \DateTime $dateCreated
     *
     * @return void
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * setDateCreatedString - from ISO8601 string
     *
     * @param $dateCreated
     *
     * @return void
     */
    public function setDateCreatedString($dateCreated)
    {
        $date = \DateTime::createFromFormat(\DateTime::ISO8601, $dateCreated);

        $this->setDateCreated($date);
    }

    /**
     * getDateCreatedString
     *
     * @return null|string
     */
    public function getDateCreatedString()
    {
        if (empty($this->dateCreated)) {
            return null;
        }

        return $this->dateCreated->format(\DateTime::ISO8601);
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        $array = get_object_vars($this);

        $array['dateCreated'] = $this->getDateCreatedString();

        return $array;
    }
}
