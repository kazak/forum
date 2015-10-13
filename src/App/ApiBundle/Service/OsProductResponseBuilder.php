<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 16.09.15
 */

namespace App\ApiBundle\Service;

use App\ApiBundle\Model\Service\BaseResponseBuilder;
use App\ApiBundle\Model\Service\OsProductResponseBuilderInterface;

/**
 * Class OsProductResponseBuilder
 * @package App\ApiBundle\Service
 */
class OsProductResponseBuilder extends BaseResponseBuilder implements OsProductResponseBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function buildGetProductsResponse($osProducts)
    {
        if (count($osProducts) == 0) {
            return [
                'status' => 404,
                'error' => [
                    'code' => 404,
                    'message' => 'Product not found'
                ]
            ];
        }

        $data = [];

        foreach ($osProducts as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName()
            ];
        }

        return [
            'status' => 200,
            'data' => $data
        ];
    }
}