<?php

/**
 * @author:     lars <lars@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 */
namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\PostCodeRepository")
 * @ORM\Table(name="postcode")
 */
class PostCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true, length=50)
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $municiplaity;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     */
    private $latitude;

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postcode;
    }

    /**
     * @param $postcode
     * @return PostCode
     */
    public function setPostCode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return PostCode
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getMuniciplaity()
    {
        return $this->municiplaity;
    }

    /**
     * @param $municiplaity
     * @return PostCode
     */
    public function setMuniciplaity($municiplaity)
    {
        $this->municiplaity = $municiplaity;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param $longitude
     * @return PostCode
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $latitude
     * @return PostCode
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }
}
