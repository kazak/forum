<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 07 2015
 */
namespace App\CoreBundle\DataFixtures\ORM\Restaurant;

use App\CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Entity\RestaurantAddress;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class AddressFixtures.
 */
class RestaurantAddressFixtures extends AbstractDollyFixture
{

    /**
     * @return int
     */
    public function getOrder()
    {
        return 25;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        /*
         * @var RestaurantAddress
         */
        $address = $this->container->get('app_core.restaurant.address.handler')->createEntity();

        $address
            ->setRestaurant($this->getReference($data['restaurantReferenceName']))
            ->setAddress($data['adr'])
            ->setPostCode($data['zip'])
            ->setPostOffice($data['city'])
            ->setLongitude($data['lat'])
            ->setLatitude($data['long']);

        return $address;
    }
}
