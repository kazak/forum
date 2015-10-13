<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 03.10.15
 */

namespace App\ApiBundle\Service;

use App\ApiBundle\Model\Service\BaseResponseBuilder;

/**
 * Class CustomerResponseBuilder
 * @package App\ApiBundle\Service
 */
class CustomerResponseBuilder extends BaseResponseBuilder
{
    /**
     * @param $user
     * @return array
     */
    public function buildGetUserCustomerResponse($user)
    {
        return [
            'status' => 200,
            'data' => $user
        ];
    }
}