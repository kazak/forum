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
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news", indexes={
 *      @ORM\Index(name="created", columns={"created"})})
 */
class News
{
    use ITDTrait, ImageTrait;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("startPage")
     *
     * @ORM\Column(type="boolean", nullable=true)
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
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->visible = true;
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