<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace WebBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Builder.
 */
class Builder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', ['childrenAttributes'=>['class'=>'nav navbar-nav navbar-right']]);

        $menu->addChild('Home', ['route' => 'web_homepage']);
        $menu->addChild('Rules', ['route' => 'web_rules']);
        $menu->addChild('blog', ['route' => 'web_blog']);

//        $menu->addChild('Settings',
//            [
//            'route' => 'app_back_office_start_settings_page',
//            'class' => 'dropdown',
//            'role' => 'presentation',
//            ]);


        // ... add more children

        return $menu;
    }

}
