<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:55
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\OrganizeRepository", )
 * @ORM\Table(name="organize")
 */
class Organize
{
    use ITDTrait, ImageTrait;

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
     * @JMS\SerializedName("background")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $background;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="admin", referencedColumnName="id", nullable=true)
     */
    protected $admin;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $visible;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true)
     */
    protected $city;

    /**
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

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
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param $background
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
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param $admin
     * @return $this
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

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
     * @param $visible
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