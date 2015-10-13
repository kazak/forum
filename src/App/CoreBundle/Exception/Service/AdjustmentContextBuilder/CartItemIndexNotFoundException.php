<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 10.10.15
 */

namespace App\CoreBundle\Exception\Service\AdjustmentContextBuilder;

use App\CoreBundle\Exception\Service\AdjustmentContextBuilderException;
use App\CoreBundle\Model\DollyCart;
use Exception;

class CartItemIndexNotFoundException extends AdjustmentContextBuilderException
{
    /**
     * @param DollyCart $cartModel
     * @param int $cartItemIndex
     */
    public function __construct(DollyCart $cartModel, $cartItemIndex)
    {
        parent::__construct("cart_item_index is not found: " . $cartItemIndex . ". Only " . count($cartModel->getItems()) . " items in cart");
    }

}