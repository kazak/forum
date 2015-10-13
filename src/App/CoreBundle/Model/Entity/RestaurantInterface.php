<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 19 05 2015
 */
namespace App\CoreBundle\Model\Entity;

use App\CoreBundle\Entity\RestaurantOpeningHour;

/**
 * Interface RestaurantInterface.
 */
interface RestaurantInterface
{
    /**
     * Get id.
     */
    public function getId();

    /**
     * Get status.
     */
    public function getStatus();

    /**
     * Get open hours.
     */
    public function getOpenHours();

    /**
     * Add open hours.
     *
     * @param RestaurantOpeningHour $openHours
     *
     * @return
     */
    public function addOpenHour(RestaurantOpeningHour $openHours);

    /**
     * Remove open hours.
     *
     * @param RestaurantOpeningHour $openHours
     *
     * @return
     */
    public function removeOpenHour(RestaurantOpeningHour $openHours);
}
