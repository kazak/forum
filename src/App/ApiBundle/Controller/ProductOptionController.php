<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 08 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sylius\Component\Variation\Model\OptionInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductOptionController
 * @package App\ApiBundle\Controller
 */
class ProductOptionController extends EntityRESTController
{
    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Lists product options.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $id
     *
     * @return mixed
     */
    public function getProductOptionsAction(Request $request, $id)
    {
        $data = [];

        $product = $this->getShopHandler()->getProduct($id);

        if ($product) {
            $data = $product->getOptions();
        }

        return $this->handleView($this->generateView($data));
    }

    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Gets product option by id.",
     *   output = "Sylius\Component\Variation\Model\OptionInterface",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when option is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $prodId
     * @param int     $optionId
     *
     * @return mixed
     */
    public function getProductOptionAction(Request $request, $prodId, $optionId)
    {
        $data = [];

        $product = $this->getShopHandler()->getProduct($prodId);

        if ($product) {
            $options = $product->getOptions()->filter(function (OptionInterface $option) use ($optionId) {
                return $optionId == $option->getId();
            });

            if (!$options->isEmpty()) {
                $data = $options->first();
            }
        }

        return $this->handleView($this->generateView($data));
    }
}
