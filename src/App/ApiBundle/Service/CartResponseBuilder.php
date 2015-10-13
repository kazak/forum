<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 01 09 2015
 */

namespace App\ApiBundle\Service;

use App\ApiBundle\Model\Service\BaseResponseBuilder;
use App\ApiBundle\Model\Service\CartResponseBuilderInterface;
use App\CoreBundle\Model\DollyCart;
use App\CoreBundle\Model\Entity\CartItemInterface;
use App\CoreBundle\Model\Entity\ProductInterface;
use App\CoreBundle\Model\Entity\ProductVariantInterface;
use App\CoreBundle\Model\Handler\CartHandlerInterface;
use App\OpenSolutionBundle\Entity\OSProduct;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use Doctrine\ORM;
use Sylius\Component\Cart\Model\CartInterface;
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartResponseBuilder
 * @package App\ApiBundle\Service
 */
class CartResponseBuilder extends BaseResponseBuilder implements CartResponseBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildGetCartResponse()
    {
        return [
                   'status' => 200,
                   'data' => $this->container->get('app_core.model.cart')->toArray()
               ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetCartitemsResponse()
    {
        return [
                   'status' => 200,
                   'data' => $this->container->get('app_core.model.cart')->toArray()['items']
               ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetCartitemResponse($cartItemId)
    {
        return [
                   'status' => 200,
                   'data' => $this->container->get('app_core.model.cart')->getItemById($cartItemId)->toArray()
               ];
    }

    /**
     * @param $cartItemId
     * @return array
     */
    public function buildDeleteCartitemResponse($cartItemId)
    {
        return [
            'status' => 204
        ];
    }

    /**
     * @param $cartItemId
     * @return array
     */
    public function buildPutCartitemResponse($cartItemId)
    {
        return [
            'status' => 201
        ];
    }

    /**
     * @inheritDoc
     */
    public function buildPostCartitemsAction()
    {
        return [
            'status' => 201,
            'data' => $this->container->get('app_core.model.cart')->toArray()
        ];
    }
}
