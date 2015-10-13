<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 8 07 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\ApiBundle\Model\Controller\ResponseBuilderHandlerInterface;
use App\CoreBundle\Handler\CartHandler;
use App\CoreBundle\Model\Handler\ProductHandlerInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package App\ApiBundle\Controller
 */
class CartController extends EntityRESTController implements ResponseBuilderHandlerInterface
{
    /**
     * @ApiDoc(
     *   section="Cart",
     *   resource = true,
     *   description = "Cart summary.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @return mixed
     */
    public function getCartAction(ParamFetcher $paramFetcher, Request $request)
    {
        $response = $this->process(
            [], 'buildGetCartResponse', 'processGetCartAction'
        );
        return $response;
    }

    /**
     * @ApiDoc(
     *   section="Cart",
     *   resource = true,
     *   description = "Lists cart items.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\Get("/cartitems")
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     *
     * @return mixed
     */
    public function getCartitemsAction(ParamFetcher $paramFetcher, Request $request)
    {
        $response = $this->process(
            [], 'buildGetCartitemsResponse', 'processGetCartitemsAction'
        );
        return $response;
    }

    /**
     * @ApiDoc(
     *   section="Cart",
     *   resource = true,
     *   description = "Creates new cartitem from the submitted data and adds it to the cart.",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postCartitemsAction(Request $request)
    {
        $response = $this->process(
            ['name', 'price', 'quantity', 'base', 'extension'], 'buildPostCartitemsAction', 'processPostCartitemsAction', false
        );
        return $response;
    }

    /**
     * @ApiDoc(
     *   section="Cart",
     *   resource = true,
     *   description = "Gets a cart item by cartItemId.",
     *   output = "App\CoreBundle\Entity\CartItem",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Cart item is not found"
     *   }
     * )
     *
     * @Annotations\Get(requirements={"cartItemId": "\d+"})
     * @param Request $request
     * @param $cartItemId
     * @return mixed
     */
    public function getCartitemAction(Request $request, $cartItemId)
    {
        $response = $this->process(
            ['cartItemId'], 'buildGetCartitemResponse', 'processGetCartitemAction', false
        );
        return $response;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Updates cart item specified by id with the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Cart item is not found"
     *   }
     * )
     *
     * @Annotations\Put(requirements={"cartItemId": "\d+", "quantity": "\d+"})
     * @param Request $request
     * @return mixed
     * @param int $cartItemId
     *
     */
    public function putCartitemsAction(Request $request, $cartItemId)
    {
        $response = $this->process(
            ['cartItemId', 'quantity'], 'buildPutCartitemResponse', 'processPutCartitemsAction', false
        );
        return $response;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Removes cartitem from the cart.",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Cart item is not found"
     *   }
     * )
     *
     * @Annotations\Delete(requirements={"id": "\d+"})
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deleteCartitemsAction(ParamFetcher $paramFetcher, Request $request, $id)
    {
        return $this->process(
            ['id'], 'buildDeleteCartitemResponse', 'processDeleteCartItemAction', false
        );
    }

    /**
     * @return string
     */
    public function getResponseBuilderServiceName()
    {
        return 'app_api.cart_response_builder.service';
    }

    /**
     * @return string
     */
    public function getHandlerServiceName()
    {
        return 'app_core.cart.handler';
    }
}
