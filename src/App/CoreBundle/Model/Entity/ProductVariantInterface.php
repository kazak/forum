<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 03 07 2015
 */
namespace App\CoreBundle\Model\Entity;

use App\CoreBundle\Entity\ProductVariantSettings;
use App\OpenSolutionBundle\Entity\OSProduct;
use Sylius\Component\Product\Model\VariantInterface as SyliusProductVariantInterface;

/**
 * Interface ProductVariantInterface.
 */
interface ProductVariantInterface extends SyliusProductVariantInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $presentation
     *
     * @return self
     */
    public function setPresentation($presentation);

    /**
     * @return OSProduct
     */
    public function getOSProduct();

    /**
     * @param OSProduct $osProduct
     *
     * @return self
     */
    public function setOSProduct(OSProduct $osProduct);

    /**
     * @param string $obtainment ex. 'takeaway'
     * @return int
     */
    public function getPrice($obtainment = null);

    /**
     * @return int
     */
    public function getEnabled();

    /**
     * @param int|bool
     *
     * @return self
     */
    public function setEnabled($enabled);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @return bool
     */
    public function isAvailable();

    /**
     * @return mixed
     */
    public function getSettings();

    /**
     * @param $settings
     * @return mixed
     */
    public function setSettings($settings);

    /**
     * @param $key
     * @return mixed
     */
    public function getSetting($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setSetting($key, $value);

    /**
     * @return mixed
     */
    public function getDefaultSettings();

    /**
     * @param ProductVariantSettings $settings
     * @return mixed
     */
    public function setDefaultSettings(ProductVariantSettings $settings);
}
