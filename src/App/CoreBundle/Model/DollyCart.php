<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 30.09.15
 */

namespace App\CoreBundle\Model;

use App\CoreBundle\Exception\Model\CartException;
use App\CoreBundle\Model\DollyCart\DollyCartListener;
use App\CoreBundle\Model\DollyCart\Item;
use App\CoreBundle\Model\Serializator\JMSSerializable;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Sylius\Component\Cart\Model\Cart as SyliusCart;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Cart
 * @package App\CoreBundle\Model
 * @ExclusionPolicy("none")
 */
class DollyCart implements ContainerAwareInterface
{

    use JMSSerializable;

    /**
     * @JMS\Type("integer")
     * @var int
     */
    private $restaurant;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $type;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $deliveryZone;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $address;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $postCode;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $postOffice;
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $directions;
    /**
     * @JMS\Type("array<App\CoreBundle\Model\DollyCart\Item>")
     * @var Item[]
     */
    private $items = [];
    /**
     * @JMS\Expose()
     * @JMS\SerializedName("items_os_categories")
     * @JMS\Type("array")
     * @var array
     */
    private $itemsOsCategories = [];
    /**
     * @JMS\Exclude()
     * @var SyliusCart
     * TODO: remove dependency on Sylius from here
     */
    private $syliusCart;
    /**
     * @JMS\Exclude()
     * @var array
     */
    private $order;
    /**
     * @JMS\Exclude()
     * @var Container
     * TODO: remove container from here
     */
    private $container;
    /**
     * @JMS\Exclude()
     * @var string
     */
    private $mode = 'summary';

    /**
     * Cart constructor.
     * @param ContainerInterface $container
     * @param string $mode
     */
    public function __construct(ContainerInterface $container, $mode = 'summary')
    {
        $this->setContainer($container);
        $this->syliusCart = $this->container->get('app_core.cart.service')->getCart();
        $this->order = $this->container->get("app_core.order.handler")->getCurrentOrder();
        $this->mode = $mode;
        $this->fetchDeliveryInfoFromOrder();
        $this->fetchItemsFromSyliusCart();
        $this->fetchItemsOsCategoriesFromSyliusCart();
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    private function fetchDeliveryInfoFromOrder()
    {
        if (isset($this->order['type'])) {
            $this->setType($this->order['type']);
        }
        if (isset($this->order['restaurant'])) {
            $this->setRestaurant($this->order['restaurant']);
        }
    }

    private function fetchItemsFromSyliusCart()
    {
        foreach ($this->syliusCart->getItems() as $syliusCartItem) {
            $this->addItem(new Item($this, $syliusCartItem, $this->mode));
        }
    }

    private function fetchItemsOsCategoriesFromSyliusCart()
    {
        foreach ($this->syliusCart->getItems() as $cartItem) {
            $this->addItemOsCategory($cartItem->getProductVariant()->getOSProduct()->getCategoryId());
        }
    }

    /**
     * @return SyliusCart
     */
    public function getSyliusCart()
    {
        return $this->syliusCart;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Item $item
     * @return int
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
        return count($this->items) - 1;
    }

    /**
     * @param $index
     */
    public function removeItemByIndex($index)
    {
        if (!isset($this->items[$index])) {
            throw new CartException("index " . $index . " is not found in cart");
        }

        unset($this->items[$index]);
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $itemsOsCategories
     */
    public function setItemsOsCategories($itemsOsCategories)
    {
        $this->itemsOsCategories = $itemsOsCategories;
    }

    /**
     * @return array
     */
    public function getItemsOsCategories()
    {
        return $this->itemsOsCategories;
    }

    /**
     * @param $itemOsCategoryId
     */
    public function addItemOsCategory($itemOsCategoryId)
    {
        $this->itemsOsCategories[] = $itemOsCategoryId;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("type")
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param int $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return string
     */
    public function getDeliveryZone()
    {
        return $this->deliveryZone;
    }

    /**
     * @param string $deliveryZone
     */
    public function setDeliveryZone($deliveryZone)
    {
        $this->deliveryZone = $deliveryZone;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return string
     */
    public function getPostOffice()
    {
        return $this->postOffice;
    }

    /**
     * @param string $postOffice
     */
    public function setPostOffice($postOffice)
    {
        $this->postOffice = $postOffice;
    }

    /**
     * @return string
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * @param string $directions
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;
    }

    /**
     * @return string
     * TODO: move to DollyCartHandler
     */
    public function toArray()
    {
        $cartArray = json_decode($this->toJson(), true);

        usort(
            $cartArray['items'],
            function($first)
            {
                if (!isset($first['base']['name'])) {
                    return false;
                }
                return $first['base']['name'] == 'Levering';
            }
        );

        return $cartArray;
    }

    /**
     * @param int $id
     * @return Item
     */
    public function getItemById($id)
    {
        foreach ($this->syliusCart->getItems() as $syliusCartItem) {
            if ($syliusCartItem->getId() == $id) {
                return new Item($this, $syliusCartItem, 'full');
            }
        }
        throw new CartException('Cart item #' . $id . ' is not found');
    }
}