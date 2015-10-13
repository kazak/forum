<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 03 06 2015
 */
namespace App\CoreBundle\Service;

use App\CoreBundle\Model\Handler\SettingsService;

/**
 * Class RestaurantDeliveryServiceService.
 */
class RestaurantDeliveryServiceService extends SettingsService
{
    /**
     * @param null $code
     *
     * @return mixed
     */
    public function getParams($code = null)
    {
        $params = parent::getParams('delivery_service');

        return $params;
    }
}
