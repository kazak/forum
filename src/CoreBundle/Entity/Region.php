<?php

namespace CoreBundle\Entity;

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
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\RegionRepository", )
 * @ORM\Table(name="region", indexes={
 *      @ORM\Index(name="slug", columns={"slug"})})
 */
class Region
{
    use ITDTrait, ImageTrait, GeoTrait, BackgroundTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("icon")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("slug")
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

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
        $this->visible = true;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
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