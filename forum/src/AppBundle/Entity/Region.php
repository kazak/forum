<?php

namespace AppBundle\Entity;

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 12:59
 */

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegionRepository", )
 * @ORM\Table(name="region")
 */
class Region
{
    use ITDTrait;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="City", mappedBy="region", cascade={"persist", "remove"})
     */
    private $cityes;

    /**
     * Region constructor.
     */
    public function __construct()
    {
        $this->cityes = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @return ArrayCollection
     */
    public function getCityes()
    {
        return $this->cityes;
    }

    /**
     * @param $cityes
     * @return $this
     */
    public function setCityes($cityes)
    {
        $this->cityes = $cityes;
        return $this;
    }

    /**
     * @param $city
     * @return $this
     */
    public function addCityes($city)
    {
        $this->cityes[] = $city;
        return $this;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function removeCityes(City $city)
    {
        $this->cityes->removeElement($city);
        return $this;
    }
}