<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 21.10.15
 * Time: 14:46
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\CityRepository")
 * @ORM\Table(name="city")
 */
class City
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
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $visible;

    /**
     *
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
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region", referencedColumnName="id", nullable=true)
     */
    protected $region;

    /**
     * @Gedmo\Slug(fields={"title"})
     *
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("slug")
     *
     * @Assert\NotBlank()
     *
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

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
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

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
}