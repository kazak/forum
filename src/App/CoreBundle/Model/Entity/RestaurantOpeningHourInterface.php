<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 19 05 2015
 */
namespace App\CoreBundle\Model\Entity;

/**
 * Interface RestaurantOpeningHourInterface.
 */
interface RestaurantOpeningHourInterface
{
    /**
     * Set service.
     *
     * @param $service
     *
     * @return
     */
    public function setService($service);

    /**
     * Get service.
     */
    public function getService();

    /**
     * Set date.
     *
     * @param $date
     *
     * @return
     */
    public function setDate($date);

    /**
     * Get date.
     */
    public function getDate();

    /**
     * Set dayOfWeek.
     *
     * @param $dayOfWeek
     *
     * @return
     */
    public function setDayOfWeek($dayOfWeek);

    /**
     * Get dayOfWeek.
     */
    public function getDayOfWeek();

    /**
     * Set openingTime.
     *
     * @param $openingTime
     *
     * @return
     */
    public function setOpeningTime($openingTime);

    /**
     * Get openingTime.
     */
    public function getOpeningTime();

    /**
     * Set closingTime.
     *
     * @param $closingTime
     *
     * @return
     */
    public function setClosingTime($closingTime);

    /**
     * Get closingTime.
     */
    public function getClosingTime();

    /**
     * Set reason.
     *
     * @param $reason
     *
     * @return
     */
    public function setReason($reason);

    /**
     * Get reason.
     */
    public function getReason();

    /**
     * Set restaurant.
     */
    public function setRestaurant();

    /**
     * Get restaurant.
     */
    public function getRestaurant();
}
