<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 29.09.15
 */

namespace App\CoreBundle\Exception\Fixtures;
use App\CoreBundle\Exception\FixturesException;
use Exception;

class SyliusCartItemsFixturesException extends FixturesException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "There are no 3 cart items in fixtures", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}