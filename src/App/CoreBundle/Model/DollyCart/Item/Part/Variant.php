<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 02.10.15
 */

namespace App\CoreBundle\Model\DollyCart\Item\Part;

use App\CoreBundle\Entity\ProductVariant;
use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart\Item\Part;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use App\OpenSolutionBundle\Entity\OSProduct;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * Class Variant
 * @package App\CoreBundle\Model\DollyCart\Item\Part
 * @ExclusionPolicy("none")
 */
class Variant
{
    use JMSSerializable;

    /**
     * @JMS\Exclude()
     * @var Part
     */
    private $part;

    /**
     * @JMS\Exclude()
     * @var ProductVariant
     */
    private $productVariant;

    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $id;
    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $osProductId;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $osProduct;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $name;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $description;
    /**
     * @JMS\Type("array")
     * @var array
     */
    private $cookingOptions;
    /**
     * @JMS\Type("boolean")
     * @var bool
     */
    private $customizable;
    /**
     * @JMS\Type("boolean")
     * @var bool
     */
    private $splittable;
    /**
     * @JMS\Type("float")
     * @var float
     */
    private $price;
    /**
     * @JMS\Type("App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients")
     * @var array
     */
    private $ingredients;
    /**
     * @JMS\Type("array")
     * @var array
     */
    private $settings;
    /**
     * @JMS\Type("array")
     * @var array
     */
    private $settingsTemplate;

    /**
     * Variant constructor.
     * @param Part $part
     * @param ProductVariant $productVariant
     */
    public function __construct(Part $part, ProductVariant $productVariant)
    {
        $this->part = $part;
        $this->productVariant = $productVariant;
        $this->fetchProductVariant();
    }

    private function fetchProductVariant()
    {
        $this->id = $this->productVariant->getId();
        $this->osProductId = $this->productVariant->getOSProduct()->getId();
        $this->osProduct = $this->productVariant->getOSProduct()->getName();
        $this->name = $this->productVariant->getSetting('name');
        $this->description = $this->productVariant->getSetting('description');
        $this->cookingOptions = [
            [
                'key' => 'normal',
                'value' => 'Normal steking'
            ],
            [
                'key' => 'light',
                'value' => 'Lettstekt'
            ]
        ];
        $this->customizable = $this->productVariant->getSetting('customizable');
        $this->splittable = $this->productVariant->getSetting('half_split_allowed');
        $this->price = $this->productVariant->getPrice($this->part->getItem()->getCart()->getType());
        $this->ingredients = new Ingredients($this, $this->productVariant);
        $this->settings = $this->productVariant->getSettings();
        $this->settingsTemplate = $this->productVariant->getDefaultSettings();
    }

    /**
     * @param Part $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @return Part
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @return Ingredients
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    private function getContainer()
    {
        return $this->part->getItem()->getCart()->getContainer();
    }
}