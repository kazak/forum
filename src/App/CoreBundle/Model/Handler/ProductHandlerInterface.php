<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 27 08 2015
 */

namespace App\CoreBundle\Model\Handler;

use App\CoreBundle\Model\Service\ProductRequestProcessorInterface;
use App\CoreBundle\Model\Entity\ProductInterface;
use App\CoreBundle\Model\Entity\ProductVariantInterface;

/**
 * Interface ProductHandlerInterface.
 */
interface ProductHandlerInterface extends EntityCrudHandlerInterface
{
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
     * @param $filters
     * @return mixed
     */
    public function getProductOption($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getProductOptions(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return mixed
     */
    public function createProductOption();

    /**
     * @param $variant
     * @return mixed
     */
    public function saveProductOption($variant);

    /**
     * @param $variant
     * @return mixed
     */
    public function removeProductOption($variant);

    /**
     * @param $filters
     * @return mixed
     */
    public function getProductOptionValue($filters);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getProductOptionValues(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return mixed
     */
    public function createProductOptionValue();

    /**
     * @param $variant
     * @return mixed
     */
    public function saveProductOptionValue($variant);

    /**
     * @param $variant
     * @return mixed
     */
    public function removeProductOptionValue($variant);

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
     * @return ProductRequestProcessorInterface
     */
    public function getRequestProcessor();
}
