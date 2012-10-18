<?php
/**
 * User Database Entity
 *
 * This is a Doctorine 2 definition file for User Objects.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace RcmLogin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Entity\PhoneNumber;
use Rcm\Entity\Address;

/**
 * User Object
 *
 * Object to contain all user data.  Use the User Manager to obtain a new
 * user.
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_users")
 */

class User
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     */
    protected $accountNumber;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ssn;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\OneToOne(targetEntity="Rcm\Entity\Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(referencedColumnName="addressId")
     */
    protected $billingAddress;

    /**
     * @ORM\OneToOne(targetEntity="Rcm\Entity\Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(referencedColumnName="addressId")
     */
    protected $shippingAddress;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Rcm\Entity\PhoneNumber",
     *     cascade={"persist", "remove"},
     *     indexBy="type"
     * )
     * @ORM\JoinTable(
     *     name="rcm_user_phone",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="user_id",
     *             referencedColumnName="accountNumber",
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="phone_id",
     *             referencedColumnName="phoneNumberId",
     *             onDelete="CASCADE"
     *         )
     *     }
     * )
     **/
    protected $phoneNumbers;

    /**
     * @ORM\Column(type="string")
     */
    protected $accountStatus;

    /**
     * @ORM\Column(type="string")
     */
    protected $accountRank;

    /**
     * @ORM\Column(type="string", name="userPassword")
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(
     *      referencedColumnName="accountNumber",
     *      onDelete="SET NULL"
     * )
     */
    protected $sponsor;

    /**
     * @ORM\OneToOne(targetEntity="AdminUser",cascade={"persist", "remove"})
     * @ORM\JoinColumn(referencedColumnName="adminId")
     */
    protected $adminInfo;

    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function setAccountRank($accountRank)
    {
        $this->accountRank = $accountRank;
    }

    public function getAccountRank()
    {
        return $this->accountRank;
    }

    public function setAccountStatus($accountStatus)
    {
        $this->accountStatus = $accountStatus;
    }

    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    public function setBillingAddress(\Rcm\Entity\Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPhoneNumber(\Rcm\Entity\PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers[$phoneNumber->getType()] = $phoneNumber;
    }

    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    public function setShippingAddress(\Rcm\Entity\Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    }

    public function getSponsor()
    {
        return $this->sponsor;
    }

    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    }

    public function getSsn()
    {
        return $this->ssn;
    }

    public function setAdminInfo(\RcmLogin\Entity\AdminUser $adminInfo)
    {
        $this->adminInfo = $adminInfo;
    }

    /**
     * @return \RcmLogin\Entity\AdminUser
     */
    public function getAdminInfo()
    {
        return $this->adminInfo;
    }

    public function getFullName()
    {
        return $this->firstName.' '.$this->lastName;
    }
}