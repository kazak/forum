<?php
 /**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 01 09 2015
 */

namespace App\ApiBundle\Service;

use App\ApiBundle\Model\Service\BaseResponseBuilder;
use App\ApiBundle\Model\Service\ProductResponseBuilderInterface;
use App\CoreBundle\Handler\ShopHandler;
use App\CoreBundle\Model\Entity\ProductInterface;
use App\CoreBundle\Model\Entity\ProductVariantInterface;
use App\CoreBundle\Model\Handler\ShopHandlerInterface;
use App\OpenSolutionBundle\Entity\OSProduct;
use App\OpenSolutionBundle\Entity\OSProductCategory;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use Doctrine\ORM;

/**
 * Class ProductResponseBuilder
 * @package App\ApiBundle\Service
 */
class ProductResponseBuilder extends BaseResponseBuilder implements ProductResponseBuilderInterface
{
    //TODO: place all $response['data'] = ... into try-catch blocks; set response error in catch block

    /**
     * {@inheritdoc}
     */
    public function buildGetProductsResponse($parameters)
    {
//        public function getEntities($type, array $filters = [], array $sorting = [], $limit = 5, $offset = 0)

        $response = $this->getResponseBase();

        $response['data'] = $this->getShopHandler()->getProducts();

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductResponse($productId)
    {
        $response = $this->getResponseBase();

        $product = $this->getShopHandler()->getProduct($productId);

        if (!($product instanceof ProductInterface)) {
            $response['status'] = 404;
            $response['error']['message'] = 'Product #' . $productId . ' not found';
            return $response;
        }

        $response['data'] = [
            'product_id' => $product->getId(),
            'number' => $product->getNumber(),
            'name' => $product->getName(),
            'description_short' => $product->getShortDescription(),
            'description_long' => $product->getDescription(),
            'image' => $product->getImage()->getPath(),
            'url' => '/customize?product=' . $product->getId(),
            'tags' => ['veg', 'hot'],
            'selected_variant_id' => null,
            'variants' => $this->buildProductVariantsData($product)
        ];

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductVariantsResponse($productId)
    {
        $response = $this->getResponseBase();

        $product = $this->getShopHandler()->getProduct($productId);

        if (!($product instanceof ProductInterface)) {
            $response['status'] = 404;
            $response['error']['message'] = 'Product #' . $productId . ' not found';
            return $response;
        }

        $response['data'] = $this->buildProductVariantsData($product);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function buildGetProductVariantResponse($productVariantId)
    {
        $response = $this->getResponseBase();

        $productVariant = $this->getShopHandler()->getProductVariant($productVariantId);

        if (!($productVariant instanceof ProductVariantInterface)) {
            $response['status'] = 404;
            $response['error']['message'] = 'Product variant #' . $productVariantId . ' not found';
            return $response;
        }

        $response['data'] = $this->buildProductVariantData($productVariant);

        return $response;
    }

    /**
     * Sub-builders
     */

    /**
     * @param ProductInterface $product
     * @return array
     * @throws ORM\EntityNotFoundException
     */
    protected function buildProductVariantsData(ProductInterface $product)
    {
        if (!($product instanceof ProductInterface)) {
            throw new ORM\EntityNotFoundException('Product not found');
        }

        $variants = $product->getVariants();

        $data = [];

        foreach ($variants as $variant) {
            $data[] = $this->buildProductVariantData($variant);
        }

        return $data;
    }

    /**
     * @param ProductVariantInterface $productVariant
     * @return array
     * @throws ORM\EntityNotFoundException
     */
    protected function buildProductVariantData(ProductVariantInterface $productVariant)
    {
        $osProduct = $productVariant->getOSProduct();

        //TODO: add OSProductInterface and check it here
        if (!($osProduct)) {
            throw new ORM\EntityNotFoundException('Assigned OS product not found');
        }

        $data = [
            'id' => $productVariant->getId(),
            'os_product_id' => $osProduct->getId(),
            'os_product' => $osProduct->getInternalName(),
            'name' => $productVariant->getSetting('name'),
            'description' => $productVariant->getSetting('description'),
            'cooking_options' => [
                [
                    'key' => 'normal',
                    'value' => 'Normal steking'
                ],
                [
                    'key' => 'light',
                    'value' => 'Lettstekt'
                ]
            ],
            'customizable' => $productVariant->getSetting('customizable'),
            'splittable' => $productVariant->getSetting('half_split_allowed'),
            'price' => $productVariant->getPrice(),
            'ingredients' => $this->buildProductVariantIngredientsData($productVariant),
            'settings' => $productVariant->getSettings(),
            'settings_template' => $productVariant->getDefaultSettings()
        ];

        return $data;
    }

    /**
     * @param OSProduct $osProduct
     * @return int
     * @throws \InvalidArgumentException
     */
    protected function buildOSProductPriceData(OSProduct $osProduct)
    {
        $obtainment = $this->getOrderObtainmentType();

        switch ($obtainment) {
            case 'takeaway':
                $data = (int) $osProduct->getPriceTakeaway();
                break;
            case 'delivery':
                $data = (int) $osProduct->getPriceDelivery();
                break;
            case 'hotel':
                $data = (int) $osProduct->getPriceHotel();
                break;
            default:
                throw new \InvalidArgumentException('Obtainment type value is ambiguous: ' . $obtainment);
        }

        return $data;
    }

    /**
     * @param ProductVariantInterface $productVariant
     * @return array
     * @throws ORM\EntityNotFoundException
     */
    protected function buildProductVariantIngredientsData(ProductVariantInterface $productVariant)
    {
        /**
         * @var OSProductCategory $category
         * @var OSProduct $ingredientProduct
         * @var OSProductIngredient $ingredientRelated
         * @var OSProduct $ingredientRelatedProduct
         */

        $data = [
            'title' => 'Ingredienser',
            'text' => '',
            'categories' => []
        ];

        $osProductHandler = $this->getOSProductHandler();
        $osProductIngredientHandler = $this->getOSProductIngredientHandler();

        $osProduct = $this->getProductVariantOSProduct($productVariant);
        $ingredients = $osProduct->getIngredients()->toArray();

        $ingredientNamesArray = [];
        $ingredientsIncluded = [];
        foreach ($ingredients as $productIngredient) {
            /**
             * @var OSProductIngredient $productIngredient
             */
            if ($productIngredient->getActive() == 1 && $productIngredient->getType() == 1) {
                $ingredientNamesArray[] = $productIngredient->getIngredient()->getName();
                $ingredientsIncluded[] = $productIngredient->getIngredient()->getId();
            }
        };
        $ingredientsText = implode(', ', $ingredientNamesArray);

        $data['text'] = $ingredientsText;

        $ingredientCategory = $osProduct->getIngredientCategory();

        if (!$ingredientCategory) {
            return $data;
        }

        // Looks like parent ingredient category never has any products associated to it directly
//        $ingredientCategories = array_merge([$ingredientCategory], $ingredientCategory->getSubCategories()->toArray());
        $ingredientCategories = $ingredientCategory->getSubCategories()->toArray();

        foreach ($ingredientCategories as $category) {

            $categoryData = [
                'os_category_id' => $category->getId(),
                'name' => $category->getName(),
                'list' => []
            ];

            $categoryIngredients = $osProductHandler->getEntities([
                'category' => $category->getId()
            ]);

            foreach ($categoryIngredients as $ingredientProduct) {

//                $isIncluded = in_array($ingredientProduct->getId(), $ingredientsIncluded);
                $isIncluded = false;
                $ambiguousIngredientProduct = $ingredientProduct;

                if (in_array($ingredientProduct->getId(), $ingredientsIncluded)) {

                    $isIncluded = true;
                    $ambiguousIngredientProduct = $ingredientProduct;

                } else {

                    $ingredientRelated = $osProductIngredientHandler->getEntityBy(['productId' => $ingredientProduct->getId()]);

                    if ($ingredientRelated) {

                        $ingredientRelatedProduct = $ingredientRelated->getIngredient();

                        if (in_array($ingredientRelatedProduct->getId(), $ingredientsIncluded)) {

                            $isIncluded = true;
                            $ambiguousIngredientProduct = $ingredientRelatedProduct;
                        }
                    }
                }

                $categoryData['list'][] = array_merge($this->buildProductVariantIngredientData($ambiguousIngredientProduct, $isIncluded), [
                    'extra' => $this->buildProductVariantIngredientData($ingredientProduct, false)
                ]);
            }

            $data['categories'][] = $categoryData;
        }

        return $data;
    }

    /**
     * @param OSProduct $ingredientProduct
     * @param bool $isIncluded
     * @return array
     */
    protected function buildProductVariantIngredientData(OSProduct $ingredientProduct, $isIncluded)
    {
        $isIncluded = (bool) $isIncluded;

        $data = [
            'os_product_id' => $ingredientProduct->getId(),
            'name' => $ingredientProduct->getName(),
            'price' => $isIncluded ? 0 : $this->buildOSProductPriceData($ingredientProduct),
            'included' => $isIncluded
        ];

        return $data;
    }

    /*
     * Helpers
     */

    protected function getProductVariantOSProduct(ProductVariantInterface $productVariant)
    {
        $osProduct = $productVariant->getOSProduct();

        //TODO: add OSProductInterface and check it here
        if (!($osProduct)) {
            throw new ORM\EntityNotFoundException('Assigned OS product not found');
        }

        return $osProduct;
    }

    /*
     * Getters
     */

    /**
     * @return ShopHandler
     */
    protected function getShopHandler()
    {
        return $this->container->get('app_core.shop.handler');
    }

    /**
     * @return mixed
     */
    protected function getOSProductHandler()
    {
        return $this->container->get('app_open_solution.product.handler');
    }

    /**
     * @return mixed
     */
    protected function getOSProductIngredientHandler()
    {
        return $this->container->get('app_open_solution.product_ingredient.handler');
    }
}
