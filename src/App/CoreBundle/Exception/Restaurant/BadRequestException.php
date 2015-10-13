<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 25.09.15
 */

namespace App\CoreBundle\Exception\Restaurant;

class BadRequestException extends Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "Bad request", $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}