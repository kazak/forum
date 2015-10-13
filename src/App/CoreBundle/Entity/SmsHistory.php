<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 07 07 2015
 */
namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\SmsHistoryRepository")
 * @ORM\Table(name="smshistory")
 **/
class SmsHistory
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="ID should be a number"
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @ORM\Column(type="string")
     */
    protected $ip;

    /**
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $verified = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $invalid_tries_count = 0;

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get verified.
     *
     * @return int
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set verified.
     *
     * @param int $verified
     *
     * @return \App\CoreBundle\Entity\SmsHistory
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get invalidTriedCount.
     *
     * @return int
     */
    public function getInvalidTriedCount()
    {
        return $this->invalid_tries_count;
    }

    /**
     * Set invalidTriedCount.
     *
     * @param int $count
     *
     * @return \App\CoreBundle\Entity\SmsHistory
     */
    public function setInvalidTriedCount($count)
    {
        $this->invalid_tries_count = $count;

        return $this;
    }

    /**
     * @return $this
     */
    public function addInvalidTriedCount()
    {
        ++$this->invalid_tries_count;

        return $this;
    }
}
