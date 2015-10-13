<?php

/**
 * @author:     marius <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 07 2015
 */
namespace App\CoreBundle\DataFixtures\ORM\Restaurant;

use App\CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class OSRestaurantChangeFixtures.
 */
class OSRestaurantChangeFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 120;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        $osRestaurantChanges = $this->container->get('app_open_solution.restaurant_change.handler')->createEntity();

        $osRestaurantChanges->setRestaurantId($this->getReference($data['referenceRestaurantName'])->getId())
            ->setRestaurant($this->getReference($data['referenceRestaurantName']))
            ->setTableName($data['tableName'])
            ->setTableId($data['tableId'])
            ->setChanges($data['changes']);

        return $osRestaurantChanges;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        if (!$this->isTestMode()) {
            $rrc = $this->container->get('app_open_solution.restaurant_change.import');
            // Update web products and categories
            $rrc->updateWebProducts(null);
            $rrc->updateWebCategories(null);
        }
    }
}
