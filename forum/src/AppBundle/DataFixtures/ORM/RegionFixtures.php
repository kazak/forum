<?php
/**
 * Created by PhpStorm.
 * User: kazak
 * Date: 12/26/15
 * Time: 11:20 PM
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Region;

class RegionFixtures extends AbstractFixtures
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param array $data
     * @return Region
     */
    protected function createEntity($data)
    {
        /** @var Region $region */
        $region = $this->container->get('region.handler')->createEntity();

        $region->setTitle($data['name']);

        return $region;
    }
}