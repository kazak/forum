<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 3 07 2015
 */
namespace App\CoreBundle\Model\Handler;

use App\CoreBundle\Model\Entity\AdjustmentInterface;
use App\CoreBundle\Model\Entity\CartItemInterface;
use App\CoreBundle\Model\Entity\ProductInterface;
use App\CoreBundle\Model\Entity\ProductVariantInterface;
use App\OpenSolutionBundle\Entity\OSProduct;
use Doctrine\ORM;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Product\Model\OptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Interface ShopHandlerInterface.
 */
interface ShopHandlerInterface
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * @param string $type
     * @param mixed  $filters
     *
     * @return mixed|null
     *
     * @throws \InvalidArgumentException
     */
    public function getEntity($type, $filters);

    /**
     * @param string $type
     * @param array  $filters
     * @param array  $sorting
     * @param int    $limit
     * @param int    $offset
     *
     * @return mixed[]
     *
     * @throws \InvalidArgumentException
     */
    public function getEntities($type, array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @param string $type
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createEntity($type);

    /**
     * @param string $type
     * @param mixed  $entity
     *
     * @throws \InvalidArgumentException
     */
    public function saveEntity($type, $entity);

    /**
     * @param string $type
     * @param mixed  $entity
     *
     * @throws \InvalidArgumentException
     */
    public function removeEntity($type, $entity);

    /**
     * @param mixed $filters
     *
     * @return ProductInterface
     */
    public function getProduct($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int   $limit
     * @param int   $offset
     *
     * @return ProductInterface[]
     */
    public function getProducts(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return ProductInterface
     */
    public function createProduct();

    /**
     * @param ProductInterface $product
     */
    public function saveProduct($product);

    /**
     * @param ProductInterface $product
     */
    public function removeProduct($product);

    /**
     * @param mixed $filters
     *
     * @return ProductVariantInterface
     */
    public function getProductVariant($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int   $limit
     * @param int   $offset
     *
     * @return ProductVariantInterface[]
     */
    public function getProductVariants(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return ProductVariantInterface
     */
    public function createProductVariant();

    /**
     * @param ProductVariantInterface $variant
     */
    public function saveProductVariant($variant);

    /**
     * @param ProductVariantInterface $variant
     */
    public function removeProductVariant($variant);

    /**
     * @param mixed $filters
     *
     * @return OrderInterface
     */
    public function getOrder($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int   $limit
     * @param int   $offset
     *
     * @return OrderInterface[]
     */
    public function getOrders(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return OrderInterface
     */
    public function createOrder();

    /**
     * @param OrderInterface $order
     */
    public function saveOrder($order);

    /**
     * @param OrderInterface $order
     */
    public function removeOrder($order);

    /**
     * @param mixed $filters
     *
     * @return OrderItemInterface
     */
    public function getOrderItem($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int   $limit
     * @param int   $offset
     *
     * @return OrderItemInterface[]
     */
    public function getOrderItems(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return OrderItemInterface
     */
    public function createOrderItem();

    /**
     * @param OrderItemInterface $orderItem
     */
    public function saveOrderItem($orderItem);

    /**
     * @param OrderItemInterface $orderItem
     */
    public function removeOrderItem($orderItem);

    /**
     * @param mixed $filters
     *
     * @return AdjustmentInterface
     */
    public function getAdjustment($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int   $limit
     * @param int   $offset
     *
     * @return AdjustmentInterface[]
     */
    public function getAdjustments(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return AdjustmentInterface
     */
    public function createAdjustment();

    /**
     * @param AdjustmentInterface $adjustment
     */
    public function saveAdjustment($adjustment);

    /**
     * @param AdjustmentInterface $adjustment
     */
    public function removeAdjustment($adjustment);

    /**
     * Creates product option from row data and adds it to the product.
     *
     * @param ProductInterface $product
     * @param array            $optionData
     *
     * @throws \InvalidArgumentException
     */
    public function generateProductOption(ProductInterface $product, array $optionData);

    /**
     * Creates option values from row data and adds them to the option.
     *
     * @param OptionInterface $option
     * @param string          $valueData
     *
     * @throws \InvalidArgumentException
     */
    public function generateProductOptionValue(OptionInterface $option, $valueData);

    /**
     * Gets product variant and os_product by ids and assigns os_product to product variant.
     *
     * @param int $productVariantId
     * @param int $osProductId
     */
    public function linkOSProductToProductVariant($productVariantId, $osProductId);

    /**
     * Sets os_product as an origin for the product variant and makes that variant enabled (and available, consequently).
     *
     * @param ProductVariantInterface $productVariant
     * @param OSProduct               $osProduct
     *
     * @throws ORM\EntityNotFoundException
     */
    public function assignOSProductToProductVariant(ProductVariantInterface $productVariant, OSProduct $osProduct);

    /**
     * Creates/updates cart item adjustment with $context and calculates its amount.
     *
     * @param CartItemInterface $cartItem
     * @param array             $context
     */
    public function updateCartItem(CartItemInterface $cartItem, array $context);
}
