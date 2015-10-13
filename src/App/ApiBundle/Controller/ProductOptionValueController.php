<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 18 10 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductOptionValueController
 * @package App\ApiBundle\Controller
 */
class ProductOptionValueController extends EntityRESTController
{
    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "Lists product option values.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     * @param int     $productId
     * @param int     $optionId
     *
     * @return mixed
     */
    public function getProductOptionValuesAction(Request $request, $productId, $optionId)
    {
        $data = [];

        $option = $this->getShopHandler()->getEntity('product_option', $optionId);

        if (!$option) {
            $error = 'Option #'.$optionId.' not found';

            return $this->handleView($this->generateView($data, $error));
        }

        $data = $option->getValues();

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
     * @param int     $productId
     * @param int     $optionId
     * @param int     $valueId
     *
     * @return mixed
     */
    public function getProductOptionValueAction(Request $request, $productId, $optionId, $valueId)
    {
        $data = [];

        $optionValue = $this->getShopHandler()->getEntity('product_option_value', $valueId);

        if (!$optionValue) {
            $error = 'Option value #'.$valueId.' not found';

            return $this->handleView($this->generateView($data, $error));
        }

        $data = $optionValue->getValue();

        return $this->handleView($this->generateView($data));
    }
}
