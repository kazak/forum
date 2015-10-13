<?php

namespace App\CoreBundle\Model\Entity;

/**
 * Class CustomerAddressInterface.
 */
interface CustomerAddressInterface
{
    /**
     * Set os address id.
     *
     * @param int $osAddressId
     *
     * @return \App\CoreBundle\Entity\CustomerAddress
     */
    public function setOsAddressId($osAddressId);

    /**
     * Get os address id.
     *
     * @return int
     */
    public function getOsAddressId();

    /**
     * @param mixed $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $directions
     *
     * @return $this
     */
    public function setDirections($directions);

    /**
     * @return mixed
     */
    public function getDirections();

    /**
     * @param int $active
     *
     * @return $this
     */
    public function setActive($active);

    /**
     * @return mixed
     */
    public function getActive();
}
