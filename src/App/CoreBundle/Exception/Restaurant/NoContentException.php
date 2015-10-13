<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 25.09.15
 */

namespace App\CoreBundle\Exception\Restaurant;

class NoContentException extends Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "No content", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}