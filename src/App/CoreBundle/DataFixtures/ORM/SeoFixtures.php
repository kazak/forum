<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 05 07 2015
 */
namespace App\CoreBundle\DataFixtures\ORM;

use App\CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class SeoFixtures.
 */
class SeoFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 26;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        $seo = $this->container->get('app_core.seo.handler')->createEntity();

        $restaurant = $this->getReference($data['referenceRestaurantName']);

        $seo
            ->setSeoTitle($data['seoTitle'])
            ->setSeoDescription($data['seoDescription'])
            ->setSeoKeywords($data['seoKeywords']);

        $restaurant->setSeo($seo);

        return $seo;
    }

    /**
     * @inheritDoc
     */
    protected function processEntity(ObjectManager $manager, $data)
    {
        parent::processEntity($manager, $data);
        $manager->persist($this->getReference($data['referenceRestaurantName']));
    }
}
