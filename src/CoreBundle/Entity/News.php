<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 13:13
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news")
 */
class News
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
     * @Assert\File( maxSize="10M")
     * @FileStore\UploadableField(mapping="photo")
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $image;


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
     * @JMS\SerializedName("startPage")
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $startPage;

    /**
     * @Assert\Type(type="CoreBundle\Entity\Region")
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id",onDelete="CASCADE", nullable=true)
     */
    protected $region;

    /**
     * @Assert\Type(type="CoreBundle\Entity\City")
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id",onDelete="CASCADE", nullable=true)
     */
    protected $city;

    /**
     * @Assert\Type("\DateTime")
     * @JMS\Expose
     * @JMS\SerializedName("created")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
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
     * @param $title
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param $image
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
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
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * @param $startPage
     * @return $this
     */
    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;

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
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }


}