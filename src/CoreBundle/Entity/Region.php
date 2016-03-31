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
    use ITDTrait, ImageTrait, GeoTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("icon")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

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
     * @var ArrayCollection<CoreBundle\Entity\City>
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
     * @var ArrayCollection
     *
     * @JMS\Expose
     * @JMS\SerializedName("partner")
     * @JMS\Type("CoreBundle\Entity\Partner")
     *
     * @ORM\OneToMany(targetEntity="Partner", mappedBy="partner")
     */
    protected $partner;

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

    /**
     * @return ArrayCollection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param $news
     * @return $this
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * @param $news
     * @return $this
     */
    public function addNews($news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * @param $news
     * @return $this
     */
    public function removeNews($news)
    {
        $this->news->removeElement($news);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param $partner
     * @return $this
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;

        return $this;
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
     * @return mixed
     */
    public function __toString()
    {
        return $this->title;
    }
}