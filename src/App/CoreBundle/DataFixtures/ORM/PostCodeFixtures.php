<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 05 07 2015
 */
namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class SeoFixtures.
 */
class PostCodeFixtures extends AbstractDollyFixture
{
    public function getOrder()
    {
        return 14;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        // TODO: Implement createEntity() method.
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = file_get_contents('src/App/CoreBundle/DataFixtures/ORM/_data/postcode-data.csv');

        if ($data) {
            $this->container->get('app_core.postcode.handler')
                 ->import(
                    explode("\r\n", utf8_encode($data))
                 );
        }
    }
}
