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
 * @ORM\Entity(repositoryClass="CityRepository", )
 * @ORM\Table(name="city")
 */
class City
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="ID should be a number"
     * )
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("title")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("image")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $image;

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
     * @JMS\SerializedName("description")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

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