<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 19 05 2015
 */
namespace App\CoreBundle\Model\Handler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface OpenHoursHandlerInterface.
 */
interface OpenHoursHandlerInterface
{
    public function getCurrentRestaurants();

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function processUpdateAction(Request $request);
}
