<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 30.03.16
 * Time: 11:17
 */

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="partner", indexes={
 *      @ORM\Index(name="slug", columns={"slug"})})
 */
class Partner
{
    use ITDTrait, ImageTrait;

    /**
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
     * @JMS\Expose
     * @JMS\SerializedName("phone")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true, unique=false)
     */
    private $phone;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("email")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true, unique=false)
     */
    private $email;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("address")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true, unique=false)
     */
    private $address;

    /**
     * @JMS\Expose
     * @JMS\SerializedName("balance")
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true, unique=false)
     */
    private $balance;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("visible")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vip;

    /**
     * Partner constructor.
     */
    public function __construct()
    {
        $this->visible = true;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * @param $vip
     * @return $this
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

}