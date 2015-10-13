<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 02.10.15
 */

namespace App\CoreBundle\Model\DollyCart\Item;

use App\CoreBundle\Entity\ProductVariant;
use App\CoreBundle\Model\DollyCart\Item;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * Class PartFull
 * @package App\CoreBundle\Model\Cart\Item
 * @ExclusionPolicy("none")
 * @AccessorOrder("custom", custom = {"productId", "number", "name", "image", "variant"})
 */
class PartFull extends Part
{
    /**
     * @JMS\Type("integer")
     * @var int
     */
    protected $productId;

    /**
     * @JMS\Type("App\CoreBundle\Model\DollyCart\Item\Part\Variant")
     * @var Variant
     */
    protected $variant;

    protected $description;

    /**
     * @inheritDoc
     */
    public function __construct(Item $item, $partName)
    {
        parent::__construct($item, $partName);
        $this->fetchCartItemPartVariant();
    }

    protected function initDescription()
    {
        parent::initDescription();
        $this->description = null;
    }

    /**
     * @return Variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    protected function fetchCartItemPartVariant()
    {
        if (!$this->productVariant) {
            return;
        }
        $this->productId = $this->productVariant->getProduct()->getId();
    }

    /**
     * @inheritDoc
     */
    protected function initProductVariant($partContext)
    {
        parent::initProductVariant($partContext);
        $this->variant = new Item\Part\Variant($this, $this->productVariant);
    }


}