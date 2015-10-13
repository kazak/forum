<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace App\BackOfficeBundle\Menu;

use App\BackOfficeBundle\Entity\HelpMenu;
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
        $menu = $factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_back_office_start_page']);
        $menu->addChild('Front pages', ['route' => 'app_back_office_front_page_index']);
        $menu->addChild('Menu', ['route' => 'app_back_office_menu_index']);
        $menu->addChild('Products', ['route' => 'app_back_office_product_index']);
        $menu->addChild('Up sale', ['route' => 'app_back_office_up_sale_index']);
        $menu->addChild(
            'Restaurants',
            ['route' => 'app_back_office_restaurant_index']
        );

        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        // you can also add sub level's to your menu's as follows

        $menu->addChild('Delivery', ['route' => 'app_back_office_delivery_index']);
        $menu->addChild('Content', ['route' => 'app_back_office_content_index']);
        $menu->addChild('Settings',
            [
            'route' => 'app_back_office_start_settings_page',
            'class' => 'dropdown',
            'role' => 'presentation',
            ]);
        $menu->addChild('Footer', ['route' => 'app_back_office_footer']);
        $menu->addChild('Help', ['route' => 'app_back_office_help_index']);
        $menu->addChild('Sitemap', ['route' => 'app_back_office_sitemap_index']);
        $menu->addChild('Logout', ['route' => 'app_back_office_logout']);

        // ... add more children

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function helpMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $hepMenu = $this->getHandler()->getMenu();

        /**
         * @var HelpMenu $helpBlock
         */
        foreach($hepMenu as $helpBlock){

            $helpers = $helpBlock->getHelp();

            if(count($helpers) > 0){

                $menu->addChild($helpBlock->getTitle(),['route' => 'app_back_office_help_show' ,
                    'routeParameters' => ['id' => $hepMenu[0]->getId()]])
                    ->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

                foreach($helpers as $help){

                   $menu[$helpBlock->getTitle()]->addChild( $help->getTitle(), [
                       'route' => 'app_back_office_help_show' ,
                       'routeParameters' => ['id' => $help->getId()]
                   ] );
                }
            }
        }

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Add',['route' => 'app_back_office_help_add']);
        }

        return $menu;
    }

    /**
     * @return \App\BackOfficeBundle\Handler\HelpHandler
     */
    public function getHandler()
    {
        return $this->container->get('app_back.help.handler');
    }
}
