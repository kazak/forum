<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 28.03.16
 * Time: 15:22
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;

/**
 * Class cityFixtures
 * @package AppBundle\DataFixtures\ORM
 */
class CityFixtures extends AbstractFixtures
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }

    /**
     * @param array $data
     * @return City
     */
    protected function createEntity($data)
    {
        /** @var City $city */
        $city = $this->container->get('city.handler')->createEntity();

        $city->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setVisible(true)
            ->setRegion($this->getReference('odessa'));

        return $city;
    }
}