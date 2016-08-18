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

        $menu->addChild('Главная', ['route' => 'web_homepage']);

        $user = $this->getUser();
        /**
         * зделать админпанель и выбор осбб
         */

        $menu->addChild('Правила', ['route' => 'web_rules']);
        $menu->addChild('Контакты', ['route' => 'web_contacts']);
        $menu->addChild('О проекте', ['route' => 'web_about_us']);

        /**
         * регистрация или профпйл
         */
        $menu->addChild('Правила', ['route' => 'web_rules']);

//        $menu->addChild('Settings',
//            [
//            'route' => 'app_back_office_start_settings_page',
//            'class' => 'dropdown',
//            'role' => 'presentation',
//            ]);

        return $menu;
    }


    private function getUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}
