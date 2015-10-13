<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 09 09 2015
 */

namespace App\CoreBundle\Model\Service;

use App\CoreBundle\Model\Entity\ProductInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ProductRequestProcessorInterface
 * @package App\CoreBundle\Model\Service
 */
interface ProductRequestProcessorInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function processGetProductsAction(Request $request);

    /**
     * Creates new product from submitted data.
     *
     * @param Request $request
     *
     * @return ProductInterface
     */
    public function processPostProductsAction(Request $request);

    /**
     * @param Request $request
     * @param $productId
     * @return mixed
     */
    public function processGetProductAction(Request $request, $productId);

    /**
     * Updates product specified by id.
     *
     * @param Request $request
     * @param $productId
     *
     * @return ProductInterface
     */
    public function processPostProductAction(Request $request, $productId);

    /**
     * @param Request $request
     * @param $productId
     * @return mixed
     */
    public function processGetProductVariantsAction(Request $request, $productId) ;

    /**
     * Creates product variant from submitted data.
     *
     * @param Request $request
     * @param $productId
     *
     * @return []
     */
    public function processPostProductVariantsAction(Request $request, $productId);

    /**
     * @param Request $request
     * @param $variantId
     * @return mixed
     */
    public function processGetProductVariantAction(Request $request, $variantId);

    /**
     * Updates product variant specified by id.
     *
     * @param Request $request
     * @param $productId
     * @param $variantId
     *
     * @return []
     */
    public function processPostProductVariantAction(Request $request, $productId, $variantId);

    /**
     * @param int $variantId
     * @param int $productId
     * @return array
     */
    public function processDeleteProductVariantAction($variantId, $productId);
}