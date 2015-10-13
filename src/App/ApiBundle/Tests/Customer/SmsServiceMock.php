<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 03.10.15
 */

namespace App\ApiBundle\Tests\Customer;

use App\CoreBundle\Handler\SmsHandler;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class SmsServiceMock extends SmsHandler
{
    /**
     * @inheritDoc
     */
    public function __construct(Container $container)
    {

    }


    /**
     * @inheritDoc
     */
    public function checkSMSCode($id, $code, $phone)
    {
        return ['error' => true];
    }

}