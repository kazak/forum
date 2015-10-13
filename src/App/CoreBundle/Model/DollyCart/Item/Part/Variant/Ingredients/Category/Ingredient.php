<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 08.10.15
 */

namespace App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category;

use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use App\OpenSolutionBundle\Entity\OSProduct;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

class Ingredient
{

    use JMSSerializable;

    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $osProductId;

    /**
     * @JMS\Type("string")
     * @var string
     */
    private $name;

    /**
     * @JMS\Type("float")
     * @var float
     */
    private $price;

    /**
     * @JMS\Type("boolean")
     * @var bool
     */
    private $included;

    /**
     * @JMS\Type("boolean")
     * @var bool
     */
    private $addon;

    /**
     * @JMS\Type("App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category\Ingredient")
     * @var Ingredient
     */
    private $extra;

    /**
     * @JMS\Exclude()
     * @var Category
     */
    private $category;

    public function __construct(Category $category, OSProductIngredient $osProductIngredient, $isExtra = false, $included = true)
    {
        $this->category = $category;
        $this->osProductId = $osProductIngredient->getIngredient()->getId();
        $this->name = $osProductIngredient->getIngredient()->getInternalName();
        $this->included = $included;

        $this->price = $included ? 0 : $this->getPrice($osProductIngredient);

        if ($osProductIngredient->getCount() > 1 && !$isExtra) {
            $this->extra = new Ingredient($category, $osProductIngredient, true, $included);
        }
    }

    /**
     * @return boolean
     */
    public function isIncluded()
    {
        return $this->included;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return Ingredient
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param Ingredient $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @return int
     */
    public function getOsProductId()
    {
        return $this->osProductId;
    }

    /**
     * @param OSProductIngredient $osProductIngredient
     * @return float
     */
    private function getPrice(OSProductIngredient $osProductIngredient)
    {
        switch ($this->getCategory()->getIngredients()->getVariant()->getPart()->getItem()->getCart()->getType()) {
            case 'takeaway':
                return $osProductIngredient->getIngredient()->getPriceTakeaway();
            case 'delivery':
                return $osProductIngredient->getIngredient()->getPriceDelivery();
            case 'hotel':
                return $osProductIngredient->getIngredient()->getPriceHotel();
            default:
                return $osProductIngredient->getIngredient()->getPriceDelivery();
        }
    }
}