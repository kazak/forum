<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 16.09.15
 */

namespace App\ApiBundle\Tests;

use App\CoreBundle\Entity\CartItem;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CartControllerTest extends WebTestCase
{
    public function testGetCartSuccess()
    {
        $responseData = $this->doSuccessRequestForGetCart('takeaway', static::createClient());
        $this->assertEquals(3, count($responseData['items']));
        $this->commonAssertionsForSuccessCartResponse($responseData);
    }

    public function testGetCartItemsSuccess()
    {
        $client = static::createClient();
        $this->initCartWithThreeItems($client);
        $this->setOrderTypeToCurrentOrder($client, 'hotel');
        $client->request('GET', '/rest/v1/shop/cartitems');

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($client->getResponse()->isSuccessful());

        foreach (['status_code', 'data', 'error', 'errorCode', 'errorMessage'] as $key) {
            $this->assertArrayHasKey($key, $response);
        }

        $this->commonAssertionsForSuccessCartItemsResponse($response['data']);
    }

    public function testGetCartItemSuccess()
    {
        $client = static::createClient();
        $this->initCartWithThreeItems($client);
        $this->setOrderTypeToCurrentOrder($client, 'takeaway');
        $url = '/rest/v1/shop/cartitems/' . $this->getAvailableCartItem($client)->getId() . '.json';
        $client->request('GET', $url);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->commonAssertionsForSuccessCartItemResponse($response['data']);
    }

    public function testPostCartItemsIncorrectFormat()
    {
        $client = static::createClient();
        $client->request('POST', '/rest/v1/shop/cartitems');

        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('errorMessage', $json);
        $this->assertEquals('Base product is not set.', $json['errorMessage']);
    }

    public function testPostCartItemsIncorrectProductVariant()
    {
        $client = static::createClient();

        $cart = json_decode(file_get_contents(__DIR__ . '/files/cart.json'), true);
        $client->getContainer()->get('session')->set('currentOrder', $cart);

        $cartItemRequest = json_decode(file_get_contents(__DIR__ . '/files/postItemIncorrect.json'), true);

        $client->request('POST', '/rest/v1/shop/cartitems', $cartItemRequest);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Product variant #12345 not found', $response['errorMessage']);
    }

    public function testPostCartItemsCorrect()
    {
        $client = static::createClient();
        $this->setOrderTypeToCurrentOrder($client, 'hotel');

        $itemsCount = 3;

        $this->postCartItems($client, 3);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals($itemsCount, count($response['data']['items']));
        $this->assertEquals(299, $response['data']['items'][1]['price']);
    }

    public function testDeleteCartItemSuccess()
    {
        $client = static::createClient();
        $client->request('POST', '/rest/v1/shop/cartitems', $this->initCartItemRequest($client));
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertGreaterThan(0, $response['data']['items'][0]['id']);
        $newCartItemId = $response['data']['items'][0]['id'];

        $this->assertNotNull($client->getContainer()->get('app_core.cart.handler')->getCartItem($newCartItemId));
        $client->request('DELETE', '/rest/v1/shop/cartitems/' . $newCartItemId);
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertNull($client->getContainer()->get('app_core.cart.handler')->getCartItem($newCartItemId));
    }

    public function testDeleteCartItemIncorrectFormat()
    {
        $client = static::createClient();
        $client->request('DELETE', '/rest/v1/shop/cartitems/d');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPutCartItemsCorrectFormat()
    {
        $client = static::createClient();

        $cartItemId = $this->getExistingCartItemId($client);
        $cartItem = $client->getContainer()->get('app_core.cart.handler')->getCartItem($cartItemId);
        $this->assertNotEquals($this->getTestPutRequest()['quantity'], $cartItem->getQuantity());
        $this->putCartItems($client, $cartItemId);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->getTestPutRequest()['quantity'], $cartItem->getQuantity(), '201 but nothing changed');
    }

    public function testPutCartItemsIdNotFound()
    {
        $client = static::createClient();

        $this->putCartItems($client, 66612121);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Cart item #66612121 not found', $response['errorMessage']);
    }

    /**
     * @param Client $client
     * @return int
     */
    private function getFirstAvailableProductVariant(Client $client)
    {
        $row = $client->getContainer()->get('doctrine')->getManager()->getRepository('AppCoreBundle:ProductVariant')
            ->findOneBy(['master' => 0]);

        $this->assertNotEmpty($row);
        return $row->getId();
    }

    /**
     * @param Client $client
     * @return int
     */
    private function getExistingCartItemId(Client $client)
    {
        $row = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:CartItem')
                      ->findOneBy(['quantity' => 1]);

        $this->assertNotEmpty($row);
        return $row->getId();
    }

    /**
     * @param Client $client
     * @param $cartItemId
     * @return array
     */
    private function putCartItems(Client $client, $cartItemId)
    {
        $request = $this->getTestPutRequest();
        $client->request(
            'PUT',
            '/rest/v1/shop/cartitems/' . $cartItemId . '.json',
            $request
        );
    }

    /**
     * @param Client $client
     * @param $orderType
     */
    private function setOrderTypeToCurrentOrder(Client $client, $orderType)
    {
        $client->getContainer()->get('session')->set('currentOrder', [
            'type' => $orderType,
            'restaurant' => 36001
        ]);
    }


    /**
     * @param Client $client
     * @return \Sylius\Component\Cart\Model\Cart
     */
    private function initCartWithThreeItems(Client $client)
    {
        $cart = $client->getContainer()->get('app_core.cart.service')->getCartWithThreeItems();
        $this->assertEquals(3, $cart->getItems()->count());
        return $cart;
    }

    /**
     * @param $orderType
     * @param Client $client
     * @return mixed
     */
    private function doSuccessRequestForGetCart($orderType, Client $client)
    {
        $this->setOrderTypeToCurrentOrder($client, $orderType);
        $this->initCartWithThreeItems($client);
        $client->request('GET', '/rest/v1/shop/cart');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($client->getResponse()->isSuccessful(), $response['errorMessage']);

        foreach (['status_code', 'data', 'error', 'errorCode', 'errorMessage'] as $key) {
            $this->assertArrayHasKey($key, $response);
        }

        $responseData = $response['data'];

        foreach (['type', 'restaurant', 'items', 'items_os_categories'] as $key) {
            $this->assertArrayHasKey($key, $responseData);
        }
        return $responseData;
    }

    /**
     * @param $responseData
     */
    private function commonAssertionsForSuccessCartResponse($responseData)
    {
        $this->assertNotEmpty($responseData['items_os_categories']);
        $this->assertNotEmpty($responseData['restaurant']);
        $this->assertNotEmpty($responseData['items'][0]);
        $firstItem = $responseData['items'][0];
        $this->assertEquals(['id', 'url', 'name', 'price', 'quantity', 'base', 'extension'], array_keys($firstItem));
        $this->assertNotEmpty($firstItem['name']);
        $this->assertEquals('Margherita', $firstItem['name']);
        $base = $firstItem['base'];
        $extension = $firstItem['extension'];
        if (!empty($extension)) {
            $this->assertNotEmpty($extension['name']);
        }
        if (empty($base)) {
            return;
        }
        $this->assertEquals(['number', 'name', 'image', 'description'], array_keys($base));
        $this->assertNotEmpty($base['number']);
        $this->assertNotEmpty($base['name']);
        $this->assertNotEmpty($base['image']);
        $baseDescription = $base['description'];
        $this->assertEquals(['name', 'text'], array_keys($baseDescription));
        $this->assertEquals('Ingredient 2, Ingredient 3(Extra), Ost på skorpen, Pepperoni, Løk', $baseDescription['text']);
    }

    /**
     * @param $responseData
     */
    private function commonAssertionsForSuccessCartItemsResponse($responseData)
    {
        $this->assertGreaterThan(0, count($responseData));
        foreach (array_keys($responseData) as $key) {
            $this->assertTrue(is_int($key));
        }
        $this->assertNotEmpty($responseData[0]);
        $firstItem = $responseData[0];
        $this->assertEquals(['id', 'url', 'name', 'price', 'quantity', 'base', 'extension'], array_keys($firstItem));
        $this->assertNotEmpty($firstItem['name']);
        $base = $firstItem['base'];
        $extension = $firstItem['extension'];
        if (!empty($extension)) {
            $this->assertNotEmpty($extension['name']);
        }
        if (empty($base)) {
            return;
        }
        $this->assertEquals(['number', 'name', 'image', 'description'], array_keys($base));
        $this->assertNotEmpty($base['number']);
        $this->assertNotEmpty($base['name']);
        $this->assertNotEmpty($base['image']);
        $baseDescription = $base['description'];
        $this->assertEquals(['name', 'text'], array_keys($baseDescription));
        $this->assertEquals('Ingredient 2, Ingredient 3(Extra), Ost på skorpen, Pepperoni, Løk', $baseDescription['text']);
    }

    /**
     * @param $responseData
     */
    private function commonAssertionsForSuccessCartItemResponse($responseData)
    {
        $this->assertEquals(['id','url','name','price','quantity','base','extension'], array_keys($responseData));
        $this->assertTrue(is_int($responseData['id']));
        $this->assertGreaterThan(0, $responseData['id']);
        $this->assertEquals('/customize?cartitem='.$responseData['id'], $responseData['url']);
        $this->assertEquals('No 1', $responseData['name']);
        $this->assertEquals(150, $responseData['price']);
        $this->assertGreaterThan(0, $responseData['quantity']);
        $base = $responseData['base'];
        if (!isset($base['name'])) {
            return;
        }
        $this->assertEquals(['product_id', 'number', 'name', 'image', 'variant'], array_keys($base));
        $baseVariant = $base['variant'];
        $this->assertEquals(['id', 'os_product_id', 'os_product', 'name', 'description', 'cooking_options', 'customizable', 'splittable', 'price', 'ingredients', 'settings', 'settings_template'], array_keys($baseVariant));
        $this->assertTrue(is_int($baseVariant['id']));
        $this->assertTrue(is_int($baseVariant['os_product_id']));
        $this->assertEquals('Stor # 1 No 1', $baseVariant['os_product']);
        $this->assertEquals('Klassisk pizzabun - Stor (40 cm)', $baseVariant['name']);
        $this->assertEquals('Our classical pizza bun is baked fresh every day.', $baseVariant['description']);
        $this->assertTrue($baseVariant['customizable']);
        $this->assertTrue($baseVariant['splittable']);
        $this->assertEquals(230, $baseVariant['price']);
        $baseVariantIngredients = $baseVariant['ingredients'];
        $this->assertEquals(['title', 'text', 'categories'], array_keys($baseVariantIngredients));
        $this->assertEquals('Ingredienser', $baseVariantIngredients['title']);
        $this->assertEquals('Ost på skorpen, Pepperoni, Løk', $baseVariantIngredients['text']);
        $this->assertEquals(2, count($baseVariantIngredients['categories']));
        $firstIngredientCategory = $baseVariantIngredients['categories'][0];
        $this->assertEquals(['os_category_id','name','list'],array_keys($firstIngredientCategory));
        $this->assertEquals(10009, $firstIngredientCategory['os_category_id']);
        $this->assertEquals('EXTRA SubCat 1', $firstIngredientCategory['name']);
        $firstIngredientCategoryList = $firstIngredientCategory['list'];
        $this->assertEquals(2, count($firstIngredientCategoryList));
        $firstProductOfFirstCategory = $firstIngredientCategoryList[0];
        $this->assertEquals(['os_product_id','name','price','included','addon','extra'], array_keys($firstProductOfFirstCategory));
        $this->assertEquals(10008, $firstProductOfFirstCategory['os_product_id']);
    }

    /**
     * @param Client $client
     * @return CartItem
     */
    private function getAvailableCartItem(Client $client)
    {
        $this->initCartWithThreeItems($client);
        $cart = $client->getContainer()->get('app_core.cart.service')->getCart();
        return $cart->getItems()->first();
    }

    /**
     * @param $client
     * @return mixed
     */
    private function initCartItemRequest($client)
    {
        $cartItemRequest = json_decode(file_get_contents(__DIR__ . '/files/postItemCorrect.json'), true);

        $cartItemRequest['base']['variant']['id'] = $this->getFirstAvailableProductVariant($client);
        return $cartItemRequest;
    }

    /**
     * @return mixed
     */
    private function getTestPutRequest()
    {
        return json_decode(file_get_contents(__DIR__ . '/files/putItemCorrect.json'), true);
    }

    /**
     * @param $client
     * @param $itemsCount
     */
    private function postCartItems($client, $itemsCount)
    {
        $cartItemRequest = $this->initCartItemRequest($client);

        for ($i = 0; $i < $itemsCount; $i++) {
            $client->request('POST', '/rest/v1/shop/cartitems', $cartItemRequest);
        }
    }
}