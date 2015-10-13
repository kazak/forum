<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 26 08 2015
 */
namespace App\ApiBundle\Service;

use App\ApiBundle\Model\Service\BaseResponseBuilder;
use App\ApiBundle\Model\Service\ResponseBuilderInterface;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResponseBuilder
 * @package App\ApiBundle\Service
 */
class ResponseBuilder extends BaseResponseBuilder implements ResponseBuilderInterface
{

    /**
     * {@inheritdoc}
     */
    public function buildGetProductsResponse($parameters)
    {
        return $this->getProductResponseBuilder()->buildGetProductsResponse($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductResponse($productId)
    {
        return $this->getProductResponseBuilder()->buildGetProductResponse($productId);
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductVariantsResponse($productId)
    {
        return $this->getProductResponseBuilder()->buildGetProductVariantsResponse($productId);
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductVariantResponse($productVariantId)
    {
        return $this->getProductResponseBuilder()->buildGetProductVariantResponse($productVariantId);
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetCartResponse()
    {
        return $this->getCartResponseBuilder()->buildGetCartResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetCartitemsResponse()
    {
        return $this->getCartResponseBuilder()->buildGetCartitemsResponse();
    }

    public function buildGetCartitemResponse($cartItemId)
    {
        return $this->getCartResponseBuilder()->buildGetCartitemResponse($cartItemId);
    }

    /**
     * @return ProductResponseBuilder
     */
    protected function getProductResponseBuilder() {
        return $this->container->get('app_api.product_response_builder.service');
    }

    /**
     * @return CartResponseBuilder
     */
    protected function getCartResponseBuilder() {
        return $this->container->get('app_api.cart_response_builder.service');
    }
}
