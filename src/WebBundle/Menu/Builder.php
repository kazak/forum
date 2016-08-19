<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace WebBundle\Menu;

use Application\Sonata\UserBundle\Entity\User;
use CoreBundle\Entity\Organize;
use Knp\Bundle\MenuBundle\DependencyInjection\Compiler\MenuBuilderPass;
use Knp\Bundle\MenuBundle\DependencyInjection\KnpMenuExtension;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Sonata\AdminBundle\Menu\MenuBuilder;
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
        /** @var MenuItem $menu */
        $menu = $factory->createItem('root', ['childrenAttributes' => ['class'=>'nav navbar-nav navbar-right'] ]);

        /** @var User $user */
        $user = $this->getUser();

        $menu->addChild('second', [
            'uri' => '#',
            'label' => 'Меню'
        ])
            ->setAttribute('glyphicon', 'glyphicon-list')
            ->setAttribute('class', 'dropdown')
            ->setLinkAttribute('class', 'dropdown-toggle')
            ->setLinkAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->setChildrenAttribute('role', 'menu');

        $menu['second']->addChild('rule', [
            'label' => 'Правила',
            'route' => 'web_rules'
        ]);
        $menu['second']->addChild('Contact', [
            'label' => 'Контакты',
            'route' => 'web_contacts'
        ]);
        $menu['second']->addChild('about', [
            'label' => 'О проектея',
            'route' => 'web_about_us'
        ]);

        if(!$user){

            /**
             * register and login
             */
            $menu->addChild('login', [
                'label' => 'Вход / Регистрация',
                'route' => 'sonata_user_security_login'
            ]);

        }else{
            $osbb = $user->getOrganizes();

            if(!is_null($osbb)){
                $menu->addChild('home', [
                    'uri' => '#',
                    'label' => 'Дом',
                ])
                    ->setAttribute('glyphicon', 'glyphicon-home')
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown')
                    ->setChildrenAttribute('class', 'dropdown-menu')
                    ->setChildrenAttribute('role', 'menu');

                /** @var Organize $home */
                foreach($osbb as $home){
                    $menu['home']->addChild($home->getTitle(),[
                        'route' => 'organize_pages',
                        'routeParameters' => ['slug' => $home->getSlug()]
                    ]);
                }

                $menu->addChild('Профайл', ['route' => 'web_rules'])
                    ->setAttribute('glyphicon', 'glyphicon-user');
            }
        }

        return $menu;
    }

    /**
     * @return bool
     */
    private function getUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return false;
        }

        return $user;
    }
}
