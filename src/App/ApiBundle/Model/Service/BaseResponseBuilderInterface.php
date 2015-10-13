<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 09 2015
 */

namespace App\ApiBundle\Model\Service;

/**
 * Interface BaseResponseBuilderInterface.
 */
interface BaseResponseBuilderInterface
{
    /**
     * @return array
     */
    public function getResponseBase();
} 