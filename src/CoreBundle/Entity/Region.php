<?php

namespace CoreBundle\Entity;

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 12:59
 */
use Doctrine\Common\Collections\ArrayCollection;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as JMS;

/**
 * @FileStore\Uploadable
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\RegionRepository", )
 * @ORM\Table(name="region")
 */
class Region
{
    use ITDTrait, ImageTrait;

    /**
     * @Assert\File( maxSize="10M")
     * @FileStore\UploadableField(mapping="photo")
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $background;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("lng")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $lng;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("lat")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $lat;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("slug")
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

    /**
     * @var Collection<CoreBundle\Entity\City>
     *
     * @JMS\Expose
     * @JMS\SerializedName("city")
     * @JMS\Type("array<CoreBundle\Entity\City>")
     *
     * @ORM\OneToMany(targetEntity="City", mappedBy="city")
     * @ORM\JoinColumn(name="city", nullable=true)
     */
    protected $city;

    /**
     * @var ArrayCollection
     *
     * @JMS\Expose
     * @JMS\SerializedName("news")
     * @JMS\Type("CoreBundle\Entity\News")
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="news")
     */
    protected $news;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->city = new ArrayCollection();
        $this->news = new ArrayCollection();

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
     * @param $lng
     * @return $this
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param $lat
     * @return $this
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param $city
     * @return $this
     */
    public function addCity($city)
    {
        $this->city[] = $city;

        return $this;
    }

    /**
     * @param $city
     * @return $this
     */
    public function removeCity($city)
    {
        $this->city->removeElement($city);

        return $this;
    }
}