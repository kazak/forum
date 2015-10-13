<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 29 05 2015
 */
namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class SettingsFixtures.
 */
class SettingsFixtures extends AbstractDollyFixture
{
    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        $setting = $this->container->get('app_core.settings.handler')->createEntity();
        $setting->setCode($data['code'])
                ->setData($data['data']);
        return $setting;
    }
}
