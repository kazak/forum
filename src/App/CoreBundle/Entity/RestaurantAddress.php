<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 09 07 2015
 */
namespace App\CoreBundle\Entity;

use App\CoreBundle\Model\Entity\Address;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="restaurant_address")
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\RestaurantAddressRepository")
 */
class RestaurantAddress extends Address
{
    /**
     * @ORM\OneToOne(targetEntity="Restaurant", inversedBy="address")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     */
    protected $restaurant;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     */
    protected $longitude;

    /**
     * @param mixed $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param Restaurant $restaurant
     *
     * @return $this
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }
}
