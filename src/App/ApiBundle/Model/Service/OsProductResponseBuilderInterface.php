<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 16.09.15
 */

namespace App\ApiBundle\Model\Service;


use App\OpenSolutionBundle\Entity\OSProduct;

interface OsProductResponseBuilderInterface
{
    /**
     * @param OSProduct[] $osProducts
     * @return array
     * @internal param array $parameters
     */
    public function buildGetProductsResponse($osProducts);
}