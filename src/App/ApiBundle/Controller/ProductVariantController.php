<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 08 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\CoreBundle\Handler\ProductHandler;
use App\CoreBundle\Model\Handler\ProductHandlerInterface;
use App\CoreBundle\Model\Entity\ProductVariantInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductVariantController
 * @package App\ApiBundle\Controller
 */
class ProductVariantController extends EntityRESTController
{
    /**
     * @return ProductHandler
     */
    protected function getHandler()
    {
        return $this->container->get('app_core.product.handler');
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Lists product variants.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     *
     * @return mixed
     */
    public function getProductVariantsAction(Request $request, $productId)
    {
        $response = $this->getHandler()->getRequestProcessor()->processGetProductVariantsAction($request, $productId);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Creates new product variant from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     *
     * @return mixed
     */
    public function postProductVariantsAction(Request $request, $productId)
    {
        $response = $this->getHandler()->getRequestProcessor()->processPostProductVariantsAction($request, $productId);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Gets product variant by id.",
     *   output = "App\CoreBundle\Entity\ProductVariant",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the product is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     * @param int     $variantId
     *
     * @return mixed
     */
    public function getProductVariantAction(Request $request, $productId, $variantId)
    {
        $response = $this->getHandler()->getRequestProcessor()->processGetProductVariantAction($request, $variantId);

        return $this->handleView($this->generateView($response));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Deletes product variant specified by id.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     * @param int     $variantId
     *
     * @return mixed
     */
    public function deleteProductVariantAction(Request $request, $productId, $variantId)
    {
        try {
            $this->getHandler()->getRequestProcessor()->processDeleteProductVariantAction($variantId, $productId);
        } catch (\Exception $e) {
            $data['status'] = $e->getCode();
            $data['error'] = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];

            return $this->handleView($this->generateView($data, $e->getCode()));
        }

        return $this->handleView($this->generateView([
            'status' => 200
        ]));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Updates product variant specified by id with the submitted data. For now can only assign OSProduct.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     * @param int     $variantId
     *
     * @return mixed
     */
    public function putProductVariantAction(Request $request, $productId, $variantId)
    {
        $response = $this->getHandler()->getRequestProcessor()->processPostProductVariantAction($request, $productId, $variantId);

        return $this->handleView($this->generateView($response));
    }
}
