<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 30.09.15
 */

namespace App\CoreBundle\Model\DollyCart\Item\Part\Variant;

use App\CoreBundle\Entity\ProductVariant;
use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart\Item\Part;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category\Ingredient;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

class Ingredients
{

    use JMSSerializable;

    /**
     * @JMS\Type("string")
     * @var string
     */
    private $title;

    /**
     * @JMS\Type("string")
     * @var string
     */
    private $text;

    /**
     * @JMS\Type("array<App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category>")
     * @var Category[]
     */
    private $categories;

    /**
     * @JMS\Exclude()
     * @var Variant
     */
    private $variant;

    /**
     * @JMS\Exclude()
     * @var ProductVariant
     */
    private $productVariant;

    /**
     * @JMS\Exclude()
     * @var \App\OpenSolutionBundle\Entity\OSProductIngredient[]
     */
    private $addedIngredients = [];

    /**
     * Ingredients constructor.
     * @param Variant $variant
     * @param ProductVariant $productVariant
     */
    public function __construct(Variant $variant, ProductVariant $productVariant)
    {
        $this->variant = $variant;
        $this->productVariant = $productVariant;
        $this->title = 'Ingredienser';
        $this->text = '';
        $this->fetchIngredientsWithCategoriesFromProductVariant();
    }

    /**
     * @return Variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param Variant $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    private function fetchIngredientsWithCategoriesFromProductVariant()
    {
        $ingredients = $this->getIngredients();

        $osProductCategories = [];
        if (count($ingredients) == 0) {
            return;
        }

        foreach ($ingredients as $osProductIngredient) {
            $this->addProductIngredientToArray($osProductIngredient, $osProductCategories, 'list');
        }

        if (empty($osProductCategories)) {
            throw new CartException("There are no product categories for ingredients for OS Product #" . $this->productVariant->getOSProduct()->getId());
        }

        foreach($osProductCategories as $categoryId => $categoryData) {
            $this->categories[$categoryId] = new Category($this, $categoryData);
        }
    }

    /**
     * @return \App\OpenSolutionBundle\Entity\OSProductIngredient[]
     */
    private function getIngredients()
    {
        return $this->productVariant->getOSProduct()->getIngredients();
    }

    /**
     * @param $osProductId
     * @param $count
     */
    public function addIngredient($osProductId, $count)
    {
        $osProductIngredient = $this->getContainer()->get('doctrine')->getRepository('AppOpenSolutionBundle:OSProductIngredient')->findOneBy(['ingredientId' => $osProductId]);

        if (!$osProductIngredient) {
            throw new CartException("OS Product #" . $osProductId . " is not ingredient");
        }

        $osProductIngredient->setCount($count);

        $this->addedIngredients[] = $osProductIngredient;

        $categoryId = $osProductIngredient->getIngredient()->getCategoryId();
        $category = $this->getCategoryByIdOrNull($categoryId);
        if (is_null($category)) {
            $this->categories[$categoryId] = $category = new Category($this, [
                'id' => $categoryId,
                'name' => $osProductIngredient->getIngredient()->getCategory()->getName()
            ]);
        }

        $category->addAdditionalIngredient($osProductIngredient);
    }

    /**
     * @return Ingredients\Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param $osProductId
     * @param $count
     */
    public function removeIngredient($osProductId, $count)
    {
        $osProductIngredient = $this->getContainer()->get('doctrine')->getRepository('AppOpenSolutionBundle:OSProductIngredient')->findOneBy(['ingredientId' => $osProductId]);

        if (!$osProductIngredient) {
            throw new CartException("OS Product #" . $osProductId . " is not ingredient");
        }

        $categoryId = $osProductIngredient->getIngredient()->getCategoryId();

        if (is_null($category = $this->getCategoryByIdOrNull($categoryId))) {
            throw new CartException("Category #" . $categoryId . " is not found for this item. Can not remove ingredient from non-existing category");
        }

        $category->removeIngredient($osProductIngredient, $count);
    }

    /**
     * @param $id
     * @return Category|null
     */
    private function getCategoryByIdOrNull($id)
    {
        if (empty($this->categories)) {
            return null;
        }

        foreach ($this->categories as $category) {
            if ($category->getOsCategoryId() == $id) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    private function getContainer()
    {
        return $this->getVariant()->getPart()->getItem()->getCart()->getContainer();
    }

    /**
     * @param OSProductIngredient $osProductIngredient
     * @param array $osProductCategories
     * @param string $categoryListType
     */
    private function addProductIngredientToArray($osProductIngredient, &$osProductCategories, $categoryListType)
    {
        $categoryId = $osProductIngredient->getProduct()->getCategory()->getId();
        if (!isset($osProductCategories[$categoryId])) {
            $osProductCategories[$categoryId]['id'] = $categoryId;
            $osProductCategories[$categoryId]['name'] = $osProductIngredient->getIngredient()->getCategory()->getName();
        }
        $osProductCategories[$categoryId][$categoryListType][] = $osProductIngredient;
    }

}