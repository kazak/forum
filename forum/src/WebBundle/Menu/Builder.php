<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace WebBundle\Menu;

use Application\Sonata\UserBundle\Entity\User;
use AppBundle\Entity\Organize;
use Knp\Bundle\MenuBundle\DependencyInjection\Compiler\MenuBuilderPass;
use Knp\Bundle\MenuBundle\DependencyInjection\KnpMenuExtension;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Sonata\AdminBundle\Menu\MenuBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
/**
 * Class Builder.
 */
class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        /**---------------------------------------------------общие ссылки по сайту------------------------------------------**/

        $menu->addChild('second', [
            'uri' => '#',
            'label' => 'Общее',
        ])
            ->setAttribute(         'glyphicon',    'glyphicon-list')
            ->setAttribute(         'class',        'dropdown')
            ->setLinkAttribute(     'class',        'dropdown-toggle')
            ->setLinkAttribute(     'data-toggle',  'dropdown')
            ->setChildrenAttribute( 'class',        'dropdown-menu')
            ->setChildrenAttribute( 'role',         'menu');

        $menu['second']->addChild('rule', [
            'label' => 'Правила',
            'route' => 'web_rules'
        ])
            ->setAttribute('glyphicon', 'glyphicon-education');

        $menu['second']->addChild('Contact', [
            'label' => 'Контакты',
            'route' => 'web_contacts'
        ])
            ->setAttribute('glyphicon', 'glyphicon-envelope');

        $menu['second']->addChild('about', [
            'label' => 'О проекте',
            'route' => 'web_about_us'
        ])
            ->setAttribute('glyphicon', 'glyphicon-blackboard');

        /**---------------------------------------------------меню юзера или вход--------------------------------------------**/

        if(!$user){

            /**
             * register and login
             */
            $menu->addChild('login', [
                'label' => 'Вход',
                'route' => 'fos_user_security_login'
            ])
                ->setAttribute('glyphicon', 'glyphicon-log-in');

        }else{
            $osbb = $user->getOrganizes();

            if(!is_null($osbb)){
                $menu->addChild('home', [
                    'uri' => '#',
                    'label' => 'Мое меню',
                ])
                    ->setAttribute(         'glyphicon',    'glyphicon-align-justify')
                    ->setAttribute(         'class',        'dropdown')
                    ->setLinkAttribute(     'class',        'dropdown-toggle')
                    ->setLinkAttribute(     'data-toggle',  'dropdown')
                    ->setChildrenAttribute( 'class',        'dropdown-menu')
                    ->setChildrenAttribute( 'role',         'menu');

                /** @var Organize $home */
                foreach($osbb as $home){
                    $menu['home']->addChild($home->getTitle(),[
                        'route'             => 'organize_pages',
                        'routeParameters'   => [ 'slug' => $home->getSlug() ]
                    ]);
                    $menu['home'][$home->getTitle()]->setAttribute('glyphicon', 'glyphicon-home');
                }

                $menu['home']->addChild('edit_home',[
                    'route' => 'profile_add_organize',
                    'label' => 'настроить',
                ])
                    ->setAttribute('glyphicon', 'glyphicon-edit');

                $menu->addChild('profile', [
                    'label' => 'Профайл',
                    'route' => 'sonata_user_profile_show'
                ])
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
