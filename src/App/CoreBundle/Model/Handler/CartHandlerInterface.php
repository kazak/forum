<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 01 09 2015
 */

namespace App\CoreBundle\Model\Handler;

use Sylius\Component\Cart\Model\CartInterface;
use App\CoreBundle\Model\Entity\CartItemInterface;

/**
 * Interface CartHandlerInterface.
 */
interface CartHandlerInterface
{
    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param $cart
     * @return mixed
     */
    public function saveCart($cart);

    /**
     * @return mixed
     */
    public function createCartItem();

    /**
     * @param $cartItemId
     * @return mixed
     */
    public function getCartItem($cartItemId);

    /**
     * @return mixed
     */
    public function createAdjustment();

    /**
     * @param CartItemInterface $cartItem
     * @param array $context
     * @return mixed
     */
    public function updateCartItemAdjustment(CartItemInterface $cartItem, array $context);
}