<?php
/**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 09 2015
 */

namespace App\ApiBundle\Model\Service;

/**
 * Interface ProductResponseBuilderInterface.
 */
interface ProductResponseBuilderInterface
{
    /**
     * @param array $parameters
     * @return array
     */
    public function buildGetProductsResponse($parameters);

    /**
     * @param int $productId
     * @return array
     */
    public function buildGetProductResponse($productId);

    /**
     * @param int $productId
     * @return array
     */
    public function buildGetProductVariantsResponse($productId);

    /**
     * @param int $productVariantId
     * @return array
     */
    public function buildGetProductVariantResponse($productVariantId);
} 