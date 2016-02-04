<?php
/**
 * Created by PhpStorm.
 * User: kazak
 * Date: 12/26/15
 * Time: 11:20 PM
 */

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Region;

class RegionFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        /** @var Region $region */
        $region = $this->container->get('region.handler')->createEntity();
        $region->setName($data['name']);
        $region->setImage($data['img']);
        $region->setLat($data['lng']);
        $region->setLng($data['lat']);

        return $region;
    }
} 