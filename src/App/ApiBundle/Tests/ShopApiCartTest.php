<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 17 07 2015
 */
namespace App\ApiBundle\Tests;

use App\CoreBundle\Model\Entity\AdjustmentInterface;
use App\CoreBundle\Model\Entity\CartItemInterface;
use App\CoreBundle\Model\Handler\ShopHandlerInterface;
use Sylius\Component\Product\Model\OptionInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShopApiCartTest extends WebTestCase
{
    /**
     *
     */
    public function testShopCartAddItem()
    {
        /**
         * @var ProductInterface $product
         * @var OptionInterface $option
         */

        $url = '/rest/v1/shop/cartitems';

        $client = self::createClient();

        $container = $client->getContainer();
        $shopHandler = $container->get('app_core.shop.handler');

        $product = array_shift($shopHandler->getProducts([], [], 1));
        $this->assertNotEmpty($product);
        $variant = $product->getVariants()->first();

        $this->assertNotEmpty($variant);
        $this->assertNotNull($variant->getId());

        $client->request('POST', $url, [
            'base' => [
                    'variant' =>
                        [
                            'id' => $variant->getId(),
                            'os_product_id' => 10001,
                            'ingredients' => []
                        ]
                     ]
                ]);

        $response = $this->followRedirect($client, $client->getResponse());

        $content = $response->getContent();
        $data = json_decode($content, true);

        $items = $data['data']['items'];

        $this->assertTrue($response->isSuccessful(), $content);

        $cartData = json_decode($response->getContent(), true);
        $this->assertTrue(!empty($cartData) && isset($cartData['data']) && 3 > count($cartData['data']['items']));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $items[0]['price']);


//        $cartItemId = $cartData['data']['items'][0]['id'];
//
//        $this->checkShopCartItemUpdate($client, $shopHandler, $cartItemId);
    }

    /**
     * @param $client
     * @param $response
     * @return mixed
     */
    public function followRedirect($client, $response)
    {
        /**
         * @var Client $client
         * @var RedirectResponse $response
         */

        if (!($response instanceof RedirectResponse)) {
            return $response;
        }

        $target = $response->getTargetUrl();
        $client->request('GET', $target);
        $response = $client->getResponse();

        if ($response->isRedirect()) {
            return $this->followRedirect($client, $response);
        }

        return $response;
    }

    /**
     * @param $client
     * @param ShopHandlerInterface $shopHandler
     * @param $cartItemId
     * TODO: need test data
     */
    private function checkShopCartItemUpdate($client, ShopHandlerInterface $shopHandler, $cartItemId)
    {
        /**
         * @var Client $client
         * @var CartItemInterface $cartItem
         * @var AdjustmentInterface $adjustment
         */

        $cartItem = $shopHandler->getEntity('order_item', $cartItemId);

        if (!$cartItem) {
            $this->assertTrue(false);
        }

        $url = '/rest/v1/shop/cartitems/'.$cartItemId;

        $client->request('POST', $url, [
            'customization' => [
                'add' => [
                    [
                        'id' => 100,
                        'count' => 1,
                    ],
                    [
                        'id' => 102,
                        'count' => 2,
                    ],
                ],
                'remove' => [
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
                    ],
                    'remove' => [
                        [
                            'id' => 103,
                            'count' => 2,
                        ],
                    ],
                ],
            ],
        ]);

        $adjustment = $cartItem->getAdjustments()->first();

        $this->assertTrue($adjustment && !is_null($adjustment) && 0 < $adjustment->getAmount());
    }
}
