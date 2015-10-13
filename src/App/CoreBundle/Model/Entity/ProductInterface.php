<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 24 08 2015
 */

namespace App\CoreBundle\Model\Entity;

use App\CoreBundle\Entity\Image;
use Sylius\Component\Product\Model\ProductInterface as SyliusProductInterface;

/**
 * Interface ProductInterface.
 */
interface ProductInterface extends SyliusProductInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * Translation helper method.
     *
     * @param string $locale
     *
     * @return ProductTranslationInterface
     *
     * @throws \RuntimeException
     */
    public function translate($locale = null);

    /**
     * @param $number
     * @return mixed
     */
    public function setNumber($number);

    /**
     * @return mixed
     */
    public function getNumber();

    /**
     * @param $shortDescription
     * @return mixed
     */
    public function setShortDescription($shortDescription);

    /**
     * @return mixed
     */
    public function getShortDescription();

    /**
     * @param Image $image
     * @param null $locale
     * @return mixed
     */
    public function setImage(Image $image, $locale=null);

    /**
     * @param null $locale
     * @return mixed
     */
    public function getImage($locale = null);

    /**
     * @param $tags
     * @return mixed
     */
    public function setTags($tags);

    /**
     * @return mixed
     */
    public function getTags();
}
