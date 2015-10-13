<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 2 07 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\CoreBundle\Model\Handler\ProductHandlerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package App\ApiBundle\Controller
 */
class ProductController extends EntityRESTController
{
    /**
     * @return ProductHandlerInterface
     */
    protected function getHandler()
    {
        return $this->container->get('app_core.product.handler');
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Lists products.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getProductsAction(Request $request)
    {
        $response = $this->getHandler()->getRequestProcessor()->processGetProductsAction($request);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Creates new product from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postProductsAction(Request $request)
    {
        $response = $this->getHandler()->getRequestProcessor()->processPostProductsAction($request);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Gets a product by id.",
     *   output = "Sylius\Component\Product\Model\Product",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the product is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return mixed
     */
    public function getProductAction(Request $request, $id)
    {
        $response = $this->getHandler()->getRequestProcessor()->processGetProductAction($request, $id);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Updates product specified by id with the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return mixed
     */
    public function postProductAction(Request $request, $id)
    {
        $response = $this->getHandler()->getRequestProcessor()->processPostProductAction($request, $id);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Deletes product specified by id.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return mixed
     */
    public function deleteProductAction(Request $request, $id)
    {
        return $this->forward('sylius.controller.product:deleteAction', [
            '_format' => $this->responseFormat,
            'id' => $id,
        ]);
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "split product.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSplittableProductsAction(Request $request, $variantId)
    {
        $response = $this->getHandler()->getRequestProcessor()->processSplitProduct($variantId);

        return $this->handleView($this->generateView($response));
    }
}
