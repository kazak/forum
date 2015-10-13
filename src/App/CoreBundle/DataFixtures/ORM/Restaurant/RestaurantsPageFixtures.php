<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 11 06 2015
 */
namespace App\CoreBundle\DataFixtures\ORM\Restaurant;

use App\CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use App\CoreBundle\Entity\RestaurantsPage;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class RestaurantsPageFixtures.
 */
class RestaurantsPageFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 110;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        /*
         * @var RestaurantsPage
         */
        $restaurantPage = new RestaurantsPage();

        $restaurantPage
            ->setMapCenterLatitude($data['mapCenterLatitude'])
            ->setMapCenterLongitude($data['mapCenterLongitude'])
            ->setSearchFieldPlaceholder($data['searchFieldPlaceholder'])
            ->setSearchButtonLabel($data['searchButtonLabel'])
            ->setTitle($data['title'])
            ->setBody($data['body']);

        return $restaurantPage;
    }
}
