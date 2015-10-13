<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 15.09.15
 */

namespace App\ApiBundle\Tests\Service;

use App\CoreBundle\Tests\KernelAwareTest;
use App\ApiBundle\Service\ProductResponseBuilder;


class ProductResponseBuilderTest extends KernelAwareTest
{
    public function testBuildGetProductVariantResponseInvalidFormat()
    {
        $this->container->get('session')->set('currentOrder', [
            'type' => 'HOTEL'
        ]);

        $builder = new ProductResponseBuilder($this->container);

        foreach ($this->getTestProductIds() as $id) {
            $message = '';
            try {
                $builder->buildGetProductVariantsResponse($id);
            } catch (\InvalidArgumentException $e) {
                $message = $e->getMessage();
            }
            $this->assertEquals('Obtainment type value is ambiguous: HOTEL', $message);
        }
    }

    /**
     * @return array
     */
    private function getTestProductIds()
    {
        $osProduct = $this->container->get('doctrine')->getRepository('AppOpenSolutionBundle:OSProduct')->findOneBy([]);

        $products = $this->container->get('doctrine')->getRepository('AppCoreBundle:ProductVariant')
            ->findBy(['osProduct'=>$osProduct], null, 1);

        $productIds = [];

        foreach ($products as $product) {
            $productIds[] = $product->getProduct()->getId();
        }

        return $productIds;
    }
}