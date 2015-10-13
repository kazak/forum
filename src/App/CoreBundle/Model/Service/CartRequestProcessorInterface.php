<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 09 09 2015
 */

namespace App\CoreBundle\Model\Service;

/**
 * Interface CartRequestProcessorInterface
 * @package App\CoreBundle\Model\Service
 */
interface CartRequestProcessorInterface
{

    public function processGetCartAction();

    public function processGetCartitemsAction();

    /**
     * @param $name
     * @param $price
     * @param $quantity
     * @param $base
     * @param $extension
     * @return mixed
     */
    public function processPostCartitemsAction($name, $price, $quantity, $base, $extension);

    /**
     * @param $cartItemId
     * @return mixed
     */
    public function processGetCartitemAction($cartItemId);
}