<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace WebBundle\Menu;

use CoreBundle\Entity\Transport;
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
        $menu = $factory->createItem('root', ['childrenAttributes' => ['class'=>'nav navbar-nav navbar-right'] ]);

        $menu->addChild('Главная', ['route' => 'web_homepage']);

        $transport = $this->container->get('transport.handler')->getEntities();

        if($transport){
            $menu->addChild('Авто', [
                'uri' => '#'
            ])
            ->setAttribute('class', 'dropdown')
            ->setLinkAttribute('class', 'dropdown-toggle')
            ->setLinkAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->setChildrenAttribute('role', 'menu');

            /** @var Transport $avto */
            foreach($transport as $avto){
                $menu['Авто']->addChild($avto->getTitle(),[
                    'route' => 'web_auto',
                    'routeParameters' => ['id' => $avto->getId()]
                ]);
                if($avto->getOriginalImage()) {
                    $menu['Авто'][$avto->getTitle()]->setAttribute('src', $avto->getWebPath());
                }
            }
        }

        $menu->addChild('Фото', ['route' => 'web_photo']);
        $menu->addChild('Видео', ['route' => 'web_video']);

        return $menu;
    }

}
