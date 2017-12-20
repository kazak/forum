<?php

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 16.12.15
 * Time: 12:55
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrganizeRepository", )
 * @ORM\Table(name="organize", indexes={
 *      @ORM\Index(name="slug", columns={"slug"})})
 */
class Organize
{
    use ITDTrait;

    /**
     * поле для отображения в урле
     * @Gedmo\Slug(fields={"title"})
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("slug")
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $slug;

    /**
     * поле для установки админа или менеджера этой сущности
     * @JMS\Type("Application\Sonata\UserBundle\Entity\User")
     *
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="organize", cascade={"persist", "remove"})
     */
    protected $admin;

    /**
     * отображение сущности на сайте
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" = true})
     */
    protected $visible;

    /**
     * город в котором находится данная организация
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("city")
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $city;

    /**
     * сущности имения относящиеся к этой организации

     */
//    protected $float;

    /**
     * информация об организации
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("info")
     *
     *
     * @ORM\Column(type="text", nullable=true, options={"default" = null})
     */
    protected $info;

    /**
     * Сообщение которое будет отображатся в самом верху форума
     * для срочного уведомления
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("message")
     *
     *
     * @ORM\Column(type="text", nullable=true, options={"default" = null})
     */
    protected $message;

    /**
     * флаг для сообщения
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("showMessage")
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default" = false})
     */
    protected $showMessage;

    /**
     * форумы относящиеся к этой организации
     * @var ArrayCollection
     *
     * @JMS\Expose
     * @JMS\SerializedName("forums")
     * @JMS\Type("Forum\Bundle\ForumBundle\Entity\Forum")
     *
     * @ORM\OneToMany(targetEntity="Forum\Bundle\ForumBundle\Entity\Forum", mappedBy="organize", cascade={"all"}, orphanRemoval=true)
     */
    protected $forums;

    /**
     * адрес организации
     *
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
//        $this->users = new ArrayCollection();
        $this->forum = new ArrayCollection();
        $this->visible = true;
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
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param $info
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * @param $forums
     * @return $this
     */
    public function setForums($forums)
    {
        $this->forums = $forums;
        return $this;
    }

    /**
     * @param $forum
     * @return $this
     */
    public function addForum($forum)
    {
        $this->forums[] = $forum;
        return $this;
    }

    /**
     * @param $forum
     */
    public function removeForum($forum)
    {
        $this->forums->removeElement($forum);
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

    /**
     * @return mixed
     */
    public function getShowMessage()
    {
        return $this->showMessage;
    }

    /**
     * @param $showMessage
     * @return $this
     */
    public function setShowMessage($showMessage)
    {
        $this->showMessage = $showMessage;
        return $this;
    }
}