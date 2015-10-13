<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 9 07 2015
 */
namespace App\CoreBundle\Model\Entity;

use Sylius\Component\Cart\Model\CartItemInterface as SyliusCartItemInterface;
use Sylius\Component\Order\Model\OrderItemInterface as SyliusOrderItemInterface;

/**
 * Interface CartItemInterface.
 */
interface CartItemInterface extends SyliusCartItemInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return ProductVariantInterface
     */
    public function getProductVariant();

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @return mixed
     */
    public function setProductVariant(ProductVariantInterface $productVariant);

    /**
     * @param SyliusOrderItemInterface $item
     *
     * @return mixed
     */
    public function equals(SyliusOrderItemInterface $item);
}
