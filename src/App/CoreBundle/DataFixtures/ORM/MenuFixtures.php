<?php

/**
 * @author:     Andriy Termovtsiy <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 07 2015
 */
namespace App\CoreBundle\DataFixtures\ORM;

use App\CoreBundle\DataFixtures\ORM\AbstractDollyFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\CoreBundle\Entity\Menu;

/**
 * Class AddressFixtures.
 */
class MenuFixtures extends AbstractDollyFixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 140;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity($data)
    {
        $menu = new Menu();
        $menu->setName($data['name'])
            ->setSiteMap($data['site_map'])
            ->setSlug($data['slug'])
            ->setStatus($data['status'])
            ->setPriority($data['priority'])
            ->setHideForSearchEngines($data['hideForSearchEngines']);
        $key = preg_replace('menu-','',$data['referenceName']);
        $menu->addProduct($this->getReference('sylius_product-'.($key + 1).''), 1);
        $menu->addProduct($this->getReference('sylius_product-'.($key * 2 + 2)), 1);
        return $menu;
    }
}
