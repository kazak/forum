<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 07 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\RestaurantAddress;
use App\CoreBundle\Model\Handler\EntityHandler;

/**
 * Class RestaurantAddressHandler.
 */
class RestaurantAddressHandler extends EntityHandler
{
    /**
     * @param $id
     *
     * @return null|RestaurantAddress
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return RestaurantAddress
     */
    public function createEntity()
    {
        return parent::createEntity();
    }
}
