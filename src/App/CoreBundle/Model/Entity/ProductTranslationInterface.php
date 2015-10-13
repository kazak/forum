<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 28 08 2015
 */

namespace App\CoreBundle\Model\Entity;

use App\CoreBundle\Entity\Image;
use Sylius\Component\Product\Model\ProductTranslationInterface as SyliusProductTranslationInterface;

/**
 * Interface ProductTranslationInterface.
 */
interface ProductTranslationInterface extends SyliusProductTranslationInterface
{
    /**
     * Get product number.
     *
     * @return string
     */
    public function getNumber();

    /**
     * Set product number.
     *
     * @param string $number
     * @return self
     */
    public function setNumber($number);

    /**
     * Get product short description.
     *
     * @return string
     */
    public function getShortDescription();

    /**
     * Set product short description.
     *
     * @param string $shortDescription
     * @return self
     */
    public function setShortDescription($shortDescription);

    /**
     * Get product image.
     *
     * @return Image
     */
    public function getImage();

    /**
     * Set product image.
     *
     * @param Image $image
     * @return self
     */
    public function setImage(Image $image);

    /**
     * Get product tags.
     *
     * @return array
     */
    public function getTags();

    /**
     * Set product tags.
     *
     * @param string $tags
     * @return self
     */
    public function setTags($tags);
} 