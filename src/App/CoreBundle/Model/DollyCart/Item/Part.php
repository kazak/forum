<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 30.09.15
 */

namespace App\CoreBundle\Model\DollyCart\Item;

use App\CoreBundle\Entity\ProductVariant;
use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart\Item;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use App\OpenSolutionBundle\Entity\OSProduct;
use App\CoreBundle\Entity\CartItem;
use App\OpenSolutionBundle\Entity\OSProductIngredient;
use Sylius\Component\Order\Model\Adjustment;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * Class Part
 * @package App\CoreBundle\Model\Cart\Item
 * @ExclusionPolicy("none")
 */
class Part
{
    use JMSSerializable;

    /**
     * @JMS\Exclude()
     * @var Item
     */
    private $item;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $number;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $name;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $image;
    /** @var array */
    protected $description;
    /**
     * @JMS\Exclude()
     * @var ProductVariant
     */
    protected $productVariant;
    /**
     * @JMS\Exclude()
     * @var Adjustment
     * TODO: remove dependency on Sylius from here
     */
    private $syliusCartItem;
    /**
     * @JMS\Exclude()
     * @var string
     */
    private $partName;

    /**
     * Part constructor.
     * @param Item $item
     * @param string $partName
     */
    public function __construct(Item $item, $partName)
    {
        $this->item = $item;
        $this->partName = $partName;
        if ($item->getSyliusCartItem()) {
            $this->syliusCartItem = $item->getSyliusCartItem();
            $this->fetchSyliusCartItemAdjustment();
        }
    }

    /**
     * @return Variant
     */
    public function getVariant()
    {
        return null;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    private function getContainer()
    {
        return $this->getItem()->getCart()->getContainer();
    }

    private function fetchSyliusCartItemAdjustment()
    {
        if ($this->syliusCartItem->getAdjustments()->count() == 0) {
            $this->getContainer()->get('logger')->err("Adjustments for sylius cart item are not found");
            return;
        }
        $context = $this->syliusCartItem->getAdjustments()->first()->getContext();
        if (!isset($context[$this->partName])) {
            if ($this->partName == 'base') {
                $this->getContainer()->get('logger')->err($this->partName . " is not found for sylius cart item context");
            }
            return;
        }

        $this->initByContext(
            $this->syliusCartItem->getAdjustments()->first()->getContext()[$this->partName]
        );
    }

    private function getIngredientsText()
    {
        if (!$this->syliusCartItem) {
            return "";
        }

        $context = $this->syliusCartItem->getAdjustments()->first()->getContext();

        $remove = isset($context[$this->partName]['customization']['remove']) ? $context[$this->partName]['customization']['remove'] : [];

        return implode(', ',
            $this->getRightNameIngrediens(
                array_merge(
                    isset($context['base']['customization']['add'])
                        ?
                    $this->getNameIngrediens(
                        $context['base']['customization']['add'],
                        $remove
                    )
                        :
                    [],
                    $this->getNameIngrediens(
                        $this->productVariant->getOSProduct()->getIngredients()->toArray(),
                        $remove
                    )
                )
            )
        );
    }

    /**
     * @param $array
     * @return array
     */
    private function getRightNameIngrediens($array)
    {

        $extraArray = array_count_values($array);
        foreach($extraArray as $key=>$val){
            if($val > 1){
                foreach($array as $rol=>$ingr) {
                    if($ingr == $key){
                        $array[$rol] = $ingr.'(Extra)';
                    }
                }
            }
        }

        $array = array_unique($array);
        return $array;
    }

    /**
     * @param OSProductIngredient[] $ingredients
     * @return array
     */
    private function getNameIngrediens($ingredients, $remove=[])
    {
        $ingredientsNames = [];
        foreach($ingredients as $productIngredient){
            if(is_array($productIngredient)){
                if($productIngredient['count']>1){
                    $ingredientsNames[] = $productIngredient['name'].'(Extra)';
                }else{
                    $ingredientsNames[] = $productIngredient['name'];
                }
            } else {
                if ($productIngredient->getType() == 1 &&
                    $productIngredient->getActive() == 1 &&
                    !isset($remove[$productIngredient->getIngredient()->getId()])
                ) {

                    $ingredientsNames[] = $productIngredient->getIngredient()->getName();
                }
            }
        }

        $ingredientsNames = array_filter ($ingredientsNames);

        return $ingredientsNames;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariant()
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariant $productVariant
     */
    public function setProductVariant($productVariant)
    {
        $this->productVariant = $productVariant;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        if (isset($this->number)) {
            return $this->number;
        }
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        }
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        if (isset($this->image)) {
            return $this->image;
        }
        return $this->image = $this->getProductVariant()->getProduct()->getImage()->getPath();
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getOsProductId()
    {
        return $this->getOSProduct()->getId();
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->getProductVariant()->getPrice($this->getItem()->getCart()->getType());
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isCustomizable()
    {
        return !!$this->getProductVariant()->getSetting('customizable');
    }

    /**
     * @return bool
     */
    public function isSplittable()
    {
        return !!$this->getProductVariant()->getSetting('half_split_allowed');
    }

    /**
     * @return array
     */
    public function getCookingOptions()
    {
        return $this->getProductVariant()->getSetting('cooking_options');
    }

    /**
     * @return \App\OpenSolutionBundle\Entity\OSProduct
     */
    public function getOSProduct()
    {
        return $this->getProductVariant()->getOSProduct();
    }

    /**
     * @param $partContext
     */
    protected function initProductVariant($partContext)
    {
        if (!isset($partContext['os_product'])) {
            throw new CartException('os_product is not found in ' . $this->partName . ' part');
        }
        if (!isset($partContext['os_product']['id'])) {
            throw new CartException('os product id is not found in ' . $this->partName);
        }
        $osProductId = $partContext['os_product']['id'];
        $osProduct = $this->getContainer()->get('app_open_solution.product.handler')->getEntity($osProductId);
        if (!$osProduct) {
            throw new CartException('os product #' . $osProductId . ' is not found');
        }
        $this->productVariant = $this->getContainer()
                ->get('doctrine')
                ->getRepository('AppCoreBundle:ProductVariant')
                ->findOneBy(['osProduct' => $osProduct]);
        if (!$this->productVariant) {
            throw new CartException('Variant for os product ' . $osProductId . ' is not found');
        }
    }

    protected function initDescription()
    {
        $this->description['name'] = 'Ingredienser';
        $this->description['text'] = $this->getIngredientsText();
    }

    /**
     * @param $partContext
     * @return $this
     */
    public function initByContext($partContext)
    {
        $this->initProductVariant($partContext);
        $product = $this->productVariant->getProduct();
        $this->setNumber($product->getNumber());
        $this->setName($product->getName());
        $this->setImage($product->getImage()->getPath());
        $this->initDescription();
        return $this;
    }

}