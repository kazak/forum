<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 07.10.15
 */

namespace App\CoreBundle\Service;


use Symfony\Component\DependencyInjection\Container;

class ProductService
{
    /** @var Container */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $osProductId
     * @return \App\CoreBundle\Entity\ProductVariant
     */
    public function getMasterVariantForOsProductId($osProductId)
    {
        $osProduct = $this->container->get('app_open_solution.product.handler')->getEntity($osProductId);

        if (!$osProduct) {
            throw new \RuntimeException("Os product #" . $osProductId . " is not found");
        }

        return $this->getMasterVariantForOsProduct($osProduct);
    }

    /**
     * @param $osProduct
     * @return \App\CoreBundle\Entity\ProductVariant
     */
    public function getMasterVariantForOsProduct($osProduct)
    {
        return $this->container->get('doctrine')->getRepository('AppCoreBundle:ProductVariant')
            ->findOneBy(['osProduct' => $osProduct, 'master' => 1]);
    }
}