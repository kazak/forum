<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 08.10.15
 */

namespace App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients;

use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category\Ingredient;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * Class Category
 * @ExclusionPolicy("none")
 * @package App\CoreBundle\Model\DollyCart\Item\Part\Variant
 */
class Category
{
    use JMSSerializable;

    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $osCategoryId;

    /**
     * @JMS\Type("string")
     * @var string
     */
    private $name;

    /**
     * @JMS\Type("array<App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category\Ingredient>")
     * @var Ingredient[]
     */
    private $list = [];

    /**
     * @JMS\Exclude()
     * @var Ingredients
     */
    private $ingredients;

    /**
     * @param Ingredients $ingredients
     * @param array $categoryData
     */
    public function __construct(Ingredients $ingredients, array $categoryData)
    {
        $this->ingredients = $ingredients;

        if (!isset($categoryData['id']) || !isset($categoryData['name'])) {
            throw new CartException("Name or id for category is not defined");
        }

        $this->osCategoryId = $categoryData['id'];
        $this->name = $categoryData['name'];

        if ($this->isListFound($categoryData, 'list')) {
            foreach ($categoryData['list'] as $osProductIngredient) {
                $this->addIncludedIngredient($osProductIngredient);
            }
        }
    }

    /**
     * @param Ingredients $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return int
     */
    public function getOsCategoryId()
    {
        return $this->osCategoryId;
    }

    /**
     * @param $osProductIngredient
     */
    private function addIncludedIngredient($osProductIngredient)
    {
        $this->list[] = new Ingredient($this, $osProductIngredient);
    }

    /**
     * @param OSProductIngredient $osProductIngredient
     */
    public function addAdditionalIngredient(OSProductIngredient $osProductIngredient)
    {
        $this->list[] = new Ingredient($this, $osProductIngredient, false, false);
    }

    /**
     * @param OSProductIngredient $ingredientForRemove
     * @param $count
     */
    public function removeIngredient(OSProductIngredient $ingredientForRemove, $count)
    {
        foreach ($this->list as $key => $ingredient) {
            if ($ingredient->getOsProductId() != $ingredientForRemove->getIngredientId()) {
                continue;
            }
            if (!$ingredient->isIncluded()) {
                $this->removeIngredientByCountFromList($count, $ingredient, $key);
            }
        }

        foreach ($this->list as $key => $ingredient) {
            if ($ingredient->getOsProductId() != $ingredientForRemove->getIngredientId()) {
                continue;
            }
            if ($ingredient->isIncluded()) {
                $this->removeIngredientByCountFromList($count, $ingredient, $key);
            }
        }
    }

    /**
     * @return Ingredient[]
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return Ingredients
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param array $categoryData
     * @param $listType
     * @return bool
     */
    private function isListFound(array $categoryData, $listType)
    {
        return isset($categoryData[$listType]) && !empty($categoryData[$listType]);
    }

    /**
     * @param $count
     * @param Ingredient $ingredient
     * @param $key
     */
    private function removeIngredientByCountFromList(&$count, Ingredient $ingredient, $key)
    {
        switch ($count) {
            case 1:
                if (is_null($ingredient->getExtra())) {
                    unset($this->list[$key]);
                } else {
                    $ingredient->setExtra(null);
                }
                break;
            case 2:
                unset($this->list[$key]);
                if (is_null($ingredient->getExtra())) {
                    $count--;
                }
                break;
        }
    }
}