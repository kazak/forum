<?php

namespace App\CoreBundle\Model\Entity;

/**
 * Class AddressInterface.
 */
interface AddressInterface
{
    /**
     * @param mixed $address
     *
     * @return $this
     */
    public function setAddress($address);

    /**
     * @return mixed
     */
    public function getAddress();

    /**
     * @param mixed $postOffice
     *
     * @return $this
     */
    public function setPostOffice($postOffice);

    /**
     * @return mixed
     */
    public function getPostOffice();

    /**
     * @param mixed $postCode
     *
     * @return $this
     */
    public function setPostCode($postCode);

    /**
     * @return mixed
     */
    public function getPostCode();

    /**
     * @return mixed
     */
    public function getId();
}
