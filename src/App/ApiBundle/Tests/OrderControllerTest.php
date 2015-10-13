<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 08.10.15
 */

namespace App\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testDeliveryFeeAddedAndRemoved()
    {
        $client = static::createClient();
        $client->request('POST', '/rest/v1/shop/ordertype.json', json_decode(
            file_get_contents(__DIR__ . '/files/post_takeaway.json'),
            true
        ));
        $this->assertEquals(0, $client->getContainer()->get('app_core.cart.service')->getCart()->getItems()->count());

        $client->request('POST', '/rest/v1/shop/ordertype.json', json_decode(
            file_get_contents(__DIR__ . '/files/post_hotel.json'),
            true
        ));
        $this->assertEquals(1, $client->getContainer()->get('app_core.cart.service')->getCart()->getItems()->count());

        $client->request('GET', '/rest/v1/shop/cart.json');
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1, count($response['data']['items']));

        $deliveryFeeItem = $response['data']['items'][0];
        $this->assertArrayNotHasKey('url', $deliveryFeeItem);
        $this->assertArrayNotHasKey('quantity', $deliveryFeeItem);
        $this->assertArrayNotHasKey('extension', $deliveryFeeItem);
        $this->assertArrayHasKey('base', $deliveryFeeItem);
        $this->assertArrayHasKey('image', $deliveryFeeItem['base']);
        $this->assertEquals('/bundles/appdolly/images/gfx/delivery.svg', $deliveryFeeItem['base']['image']);
        $this->assertArrayHasKey('name', $deliveryFeeItem['base']);
        $this->assertEquals('Levering', $deliveryFeeItem['base']['name']);
    }
}