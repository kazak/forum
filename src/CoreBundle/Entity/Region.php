<?php

namespace CoreBundle\Entity;

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 12:59
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\RegionRepository")
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $background;

    /**
     * @ORM\Column(type="string")
     */
    protected $lng;

    /**
     * @ORM\Column(type="string")
     */
    protected $lat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $visible;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     * @return $this
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }


}