<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace App\CoreBundle\Entity;

use App\CoreBundle\Model\Entity\RestaurantOpeningHourInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\RestaurantOpeningHourRepository")
 * @ORM\Table(name="restaurant_opening_hours")
 */
class RestaurantOpeningHour implements RestaurantOpeningHourInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="openHours")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $restaurant;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $service;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="smallint", nullable=true, name="day_of_week")
     */
    protected $dayOfWeek;

    /**
     * @ORM\Column(type="time", nullable=false, name="opening_time")
     */
    protected $openingTime;

    /**
     * @ORM\Column(type="time", nullable=false, name="closing_time")
     */
    protected $closingTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $reason;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get service.
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set service.
     *
     * @param string $service
     *
     * @return RestaurantOpeningHour
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return RestaurantOpeningHour
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get dayOfWeek.
     *
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * Set dayOfWeek.
     *
     * @param int $dayOfWeek
     *
     * @return RestaurantOpeningHour
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * Get openingTime.
     *
     * @return \DateTime
     */
    public function getOpeningTime()
    {
        return $this->openingTime;
    }

    /**
     * Set openingTime.
     *
     * @param \DateTime $openingTime
     *
     * @return RestaurantOpeningHour
     */
    public function setOpeningTime($openingTime)
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    /**
     * Get closingTime.
     *
     * @return \DateTime
     */
    public function getClosingTime()
    {
        return $this->closingTime;
    }

    /**
     * Set closingTime.
     *
     * @param \DateTime $closingTime
     *
     * @return RestaurantOpeningHour
     */
    public function setClosingTime($closingTime)
    {
        $this->closingTime = $closingTime;

        return $this;
    }

    /**
     * Get reason.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set reason.
     *
     * @param string $reason
     *
     * @return RestaurantOpeningHour
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get restaurant.
     *
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Set restaurant.
     *
     * @param Restaurant $restaurant
     *
     * @return RestaurantOpeningHour
     */
    public function setRestaurant($restaurant = null)
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
