<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:55
 */

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CoreBundle\Repositories\OrganizeRepository", )
 * @ORM\Table(name="organize")
 */
class Organize
{
    use ITDTrait, ImageTrait, GeoTrait;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinTable(name="admin_organize",
     *      joinColumns={@ORM\JoinColumn(name="id_organize", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_admin", referencedColumnName="id")}
     *      )
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
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinTable(name="user_organize",
     *      joinColumns={@ORM\JoinColumn(name="id_organize", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id")}
     *      )
     */
    protected $users;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("info")
     *
     *
     * @ORM\Column(type="text", nullable=true, options={"default" = null})
     */
    protected $info;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("message")
     *
     *
     * @ORM\Column(type="text", nullable=true, options={"default" = null})
     */
    protected $message;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("forums")
     * @JMS\Type("CoreBundle\Entity\Forum")
     *
     * @ORM\OneToMany(targetEntity="Forum", mappedBy="organize")
     */
    protected $forum;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("address")
     *
     *
     * @ORM\Column(type="text", nullable=true, options={"default" = null})
     */
    protected $address;

    /**
     * Organize constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->forum = new ArrayCollection();
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

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param $users
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param $users
     * @return $this
     */
    public function addUser($users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param mixed $forum
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    /**
     * @param $forum
     * @return $this
     */
    public function addForum($forum)
    {
        $this->forum[] = $forum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }


}