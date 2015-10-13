<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 03.10.15
 */

namespace App\ApiBundle\Model\Controller;


interface ResponseBuilderHandlerInterface
{
    /**
     * @return string
     */
    public function getResponseBuilderServiceName();

    /**
     * @return string
     */
    public function getHandlerServiceName();
}