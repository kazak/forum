<?php

namespace App\CoreBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address.
 */
abstract class Address implements AddressInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", name="post_code")
     */
    protected $postCode;

    /**
     * @ORM\Column(type="string", nullable=true, name="post_office")
     */
    protected $postOffice;

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @param mixed $address
     *
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $postOffice
     *
     * @return $this
     */
    public function setPostOffice($postOffice)
    {
        $this->postOffice = $postOffice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostOffice()
    {
        return $this->postOffice;
    }

    /**
     * @param mixed $postCode
     *
     * @return $this
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
