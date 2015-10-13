<?php

/**
 * @author:     marius <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 05 2015
 */
namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class RestaurantFixtures.
 */
class RestaurantFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        $restaurant = $this->container->get('app_core.restaurant.handler')->createEntity();

        $restaurant->setId($data['platsnr'])
                   ->setName($data['namn'])
                   ->setActive($data["aktiv"])
                   ->setTimeTakeaway(20)
                   ->setTimeDelivery(60)
                   ->setWarrantyVoided(0)
                   ->setTitle("Dolly Dimple's ".strtolower($data['namn']))
                   ->setDescription('Some description goes here...')
                   ->setFeatures('Features is perhaps a duplicate of description and can be removed....')
                   ->setVisible($data['visible']);

        return $restaurant;
    }
}
