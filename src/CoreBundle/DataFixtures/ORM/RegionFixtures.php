<?php
/**
 * Created by PhpStorm.
 * User: kazak
 * Date: 12/26/15
 * Time: 11:20 PM
 */

namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

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
        $region = $this->container->get('region.handler')->createEntity();
        $region->setName($data['name']);
        $region->setLat($data['lng']);
        $region->setLng($data['lat']);

        return $region;
    }
} 