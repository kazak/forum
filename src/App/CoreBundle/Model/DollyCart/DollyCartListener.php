<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 07.10.15
 */

namespace App\CoreBundle\Model\DollyCart;

use App\CoreBundle\Model\DollyCart;
use App\CoreBundle\Model\DollyCart\Item\Part;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients;
use App\CoreBundle\Model\DollyCart\Item\Part\Variant\Ingredients\Category;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class DollyCartListener implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_deserialize', 'method' => 'initParentObjects']
        ];
    }

    public function initParentObjects(ObjectEvent $event)
    {
        $object = $event->getObject();

        if ($object instanceof DollyCart) {
            foreach ($object->getItems() as $item) {
                $item->setCart($object);
            }
        }

        if ($object instanceof Item && !is_null($object->getBase())) {
            $object->getBase()->setItem($object);

            if (!is_null($object->getExtension())) {
                $object->getExtension()->setItem($object);
            }
        }

        if ($object instanceof Part && !is_null($object->getVariant())) {
            $this->initPart($object);
        }

        if ($object instanceof Variant && !is_null($object->getIngredients())) {
            $object->getIngredients()->setVariant($object);
        }

        if ($object instanceof Ingredients && !empty($object->getCategories())) {
            foreach ($object->getCategories() as $category) {
                $category->setIngredients($object);
            }
        }

        if ($object instanceof Category && !empty($object->getList())) {
            foreach ($object->getList() as $ingredient) {
                $ingredient->setCategory($object);
            }
        }
    }

    /**
     * @param Part $part
     */
    private function initPart(Part $part)
    {
        $part->getVariant()->setPart($part);
    }
}