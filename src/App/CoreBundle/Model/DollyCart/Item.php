<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 30.09.15
 */

namespace App\CoreBundle\Model\DollyCart;

use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart;
use App\CoreBundle\Model\DollyCart\Item\Part;
use App\CoreBundle\Model\DollyCart\Item\PartFull;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use Sylius\Component\Cart\Model\CartItem;
use App\CoreBundle\Model\Entity\CartItemInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * Class Item
 * @package App\CoreBundle\Model\DollyCart
 * @ExclusionPolicy("none")
 * @AccessorOrder("custom", custom = {"id", "url", "name", "price", "quantity", "base", "extension"})
 */
class Item
{
    use JMSSerializable;

    /**
     * @JMS\Exclude()
     * @var DollyCart
     */
    private $cart;
    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $id = 0;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $url;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $name = '';
    /**
     * @JMS\Type("float")
     * @var int
     */
    private $price;
    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $quantity;
    /**
     * @JMS\Exclude()
     * @var Part[]
     */
    private $parts = [null, null];
    /**
     * @JMS\Type("App\CoreBundle\Model\DollyCart\Item\PartFull")
     * @var Part
     */
    private $base = null;
    /**
     * @JMS\Type("App\CoreBundle\Model\DollyCart\Item\PartFull")
     * @var Part
     */
    private $extension = null;
    /**
     * @JMS\Exclude()
     * @var CartItem
     * TODO: remove dependency on Sylius from here
     */
    private $syliusCartItem;
    /**
     * @JMS\Exclude()
     * @var string
     */
    private $mode;

    /**
     * Item constructor.
     * @param DollyCart $cart
     * @param CartItem $syliusCartItem
     * @param string $mode
     */
    public function __construct(DollyCart $cart, CartItem $syliusCartItem, $mode = 'summary')
    {
        $this->cart = $cart;
        $this->syliusCartItem = $syliusCartItem;
        $this->mode = $mode;
        $this->fetchSyliusCartItem($syliusCartItem);
    }

    /**
     * @return CartItem
     */
    public function getSyliusCartItem()
    {
        return $this->syliusCartItem;
    }

    /**
     * @param CartItemInterface $syliusCartItem
     */
    private function fetchSyliusCartItem(CartItemInterface $syliusCartItem)
    {
        $this->setId($syliusCartItem->getId());
        $this->setPrice($syliusCartItem->getUnitPrice());
        if ($syliusCartItem->getProductVariant()->getOSProduct()->getId() == $this->getCart()->getContainer()->getParameter('delivery_fee_os_id')) {
            $this->initForDeliveryFeeItem();
            return;
        }
        $this->setQuantity($syliusCartItem->getQuantity());
        $this->setUrl('/customize?cartitem=' . $syliusCartItem->getId());
        $this->setBase($this->generatePart('base'));
        $this->setExtension($this->generatePart('extension'));
        if (!$syliusCartItem->getProductVariant()) {
            throw new CartException('Product variant is not found for cart item');
        }
        $this->setName($syliusCartItem->getProductVariant()->getProduct()->getName());
    }

    private function initForDeliveryFeeItem()
    {
        $base = new Part($this, 'base');
        $base->setImage('/bundles/appdolly/images/gfx/delivery.svg');
        $base->setName('Levering');
        $this->setBase($base);
    }

    /**
     * @return DollyCart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param DollyCart $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("base")
     * @return Part
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param Part $base
     */
    public function setBase(Part $base)
    {
        $this->base = $this->parts[0] = $base;
    }

    /**
     * @return Part
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param Part $extension
     */
    public function setExtension(Part $extension)
    {
        $this->extension = $this->parts[1] = $extension;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName()
    {
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
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @param Part $part
     */
    public function addPart(Part $part)
    {
        $this->parts[] = $part;
    }

    public function removeExtension()
    {
        $this->extension = new Part($this, "extension");
    }

    /**
     * @param $extensionContext
     */
    public function updateExtensionByContext($extensionContext)
    {
        $this->setExtension(
            $this->generatePartByContext($extensionContext)
        );
    }

    /**
     * @param $partContext
     * @param string $mode
     * @param string $partName
     * @return Part
     */
    private function generatePartByContext($partContext, $mode = "full", $partName = "extension")
    {
        $this->mode = $mode;
        return $this->generatePart($partName)->initByContext($partContext);
    }

    /**
     * @param $partName
     * @return Part
     */
    private function generatePart($partName)
    {
        switch ($this->mode) {
            case 'summary':
                return new Part($this, $partName);
            case 'full':
                return new PartFull($this, $partName);
            default:
                throw new CartException('Unknown item mode');
        }
    }
}