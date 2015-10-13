<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 18 06 2015
 */
namespace App\DollyBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShopController
 * @package App\DollyBundle\Controller
 */
class ShopController extends DefaultController
{
    /**
     * @return \App\CoreBundle\Handler\ShopHandler
     */
    protected function getShopHandler()
    {
        return $this->container->get('app_core.shop.handler');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function indexAction()
    {
        //        ini_set('xdebug.var_display_max_depth', -1);
//        ini_set('xdebug.var_display_max_children', -1);
//        ini_set('xdebug.var_display_max_data', -1);

        $entity = $this->getUser();

        if(is_null($entity)){

            return $this->redirectToRoute('dolly_homepage');

        }

        $shopHandler = getShopHandler();

        $cart = array_shift($shopHandler->getOrders([], [], 1));

//        $cartItem = $shopHandler->getOrderItem(38);
        $cartItem = array_shift($cart->getItems()->toArray());

        $shopHandler->updateCartItem($cartItem, [
            'customization' => [
                'add' => [
                    [
                        'id' => 100,
                        'count' => 1,
                    ],
                    [
                        'id' => 101,
                        'count' => 1,
                    ],
                    [
                        'id' => 103,
                        'count' => 1,
                    ],
                    [
                        'id' => 105,
                        'count' => 1,
                    ],
                ],
            ],
            'extension' => [
                'id' => 11,
                'customization' => [
                    'add' => [
                        [
                            'id' => 101,
                            'count' => 1,
                        ],
                        [
                            'id' => 102,
                            'count' => 1,
                        ],
                        [
                            'id' => 103,
                            'count' => 1,
                        ],
                        [
                            'id' => 105,
                            'count' => 1,
                        ],
                    ],
                ],
            ],
        ]);

        $adjustment = $cartItem->getAdjustments()->first();

        var_dump($adjustment->getContext());
        var_dump($adjustment->getAmount());

        return $this->render('AppDollyBundle:Default:index.html.twig');
    }

    public function productsIndexAction()
    {
        //TODO: this method
    }

    /**
     * @return RedirectResponse|Response
     */
    public function cartIndexAction()
    {
        $entity = $this->getUser();

        if(is_null($entity)){

            return $this->redirectToRoute('dolly_homepage');

        }

        $cart = $this->getShopHandler()->processGetCartAction();

        if(!isset($cart['data']['items_os_categories'])){
            $cart['data']['items_os_categories'] = [];
        }
        $menuHandler = $this->container->get('app_core.menu.handler');
        $menuList = $menuHandler->menu();

        $upsale = $this->getShopHandler()->getUpSaleProduct($cart['data']['items_os_categories'], $cart['data']['type']);

        return $this->render('AppDollyBundle:Dolly:cart.html.twig', [
            'upsale' => $upsale,
            'topMenuItem' => $menuList['data'][0]
        ]);
    }
}
