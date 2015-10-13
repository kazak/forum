<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 18.09.15
 */

namespace App\ApiBundle\Tests;
use App\CoreBundle\Entity\Product;
use App\CoreBundle\Entity\ProductVariant;
use App\OpenSolutionBundle\Entity\OSProduct;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductVariantControllerTest extends WebTestCase
{
    public function testPostProductVariants()
    {
        $client = static::createClient();
        $request = json_decode(file_get_contents(__DIR__ . '/files/postProductVariant.json'), true);

        $product = $this->getAvailableProduct($client);
        $request['productId'] = $this->getAvailableProduct($client)->getId();
        $request['settings_id'] = $this->getAvailableVariant($client)->getDefaultSettings()->getId();;

        $client->request(
            'POST',
            '/rest/v1/shop/products/' . $product->getId() . '/variants',
            $request
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($response['data']['name']);
    }

    public function testGetProductVariants()
    {
        $client = static::createClient();
        $variant = $this->getAvailableVariant($client);

        $client->request(
            'GET',
            '/rest/v1/shop/products/' . $variant->getProduct()->getId() . '/variants'
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($response['data'][0]['os_product']);
    }

    public function testPutProductVariantsCorrect()
    {
        $client = static::createClient();

        $osProductId = $this->getAvailableOSProduct($client)->getId();
        $setting = $this->getAvailableSetting($client);
        $settingId = $setting->getId();
        $settingName = $setting->getName();

        $responseData = $this->putProductVariants($client, $requestData, $settingId, $settingName, $osProductId)['data'];

        $this->assertEquals($requestData['settings'], $responseData['settings']);
        $this->assertEquals($requestData['os_product_id'], $responseData['os_product_id']);
        $this->assertEquals($requestData['settings_template']['id'],$responseData['settings_template']['id']);
        $this->assertEquals($requestData['settings_template']['name'],$responseData['settings_template']['name']);
    }

    public function testPutProductVariantsIncorrectOsProduct()
    {
        $client = static::createClient();

        $osProductId = 66666;
        $setting = $this->getAvailableSetting($client);
        $settingId = $setting->getId();
        $settingName = $setting->getName();

        $requestData = [];
        $responseData = $this->putProductVariants($client, $requestData, $settingId, $settingName, $osProductId);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertTrue($responseData['error']);
        $this->assertEquals(404, $responseData['errorCode']);
        $this->assertEquals('OSProduct #'.$osProductId.' is not found', $responseData['errorMessage']);
    }

    public function testPutProductVariantsIncorrectDefaultSetting()
    {
        $client = static::createClient();

        $osProductId = $this->getAvailableOSProduct($client)->getId();
        $setting = $this->getAvailableSetting($client);
        $settingId = 6666;
        $settingName = $setting->getName();

        $requestData = [];
        $responseData = $this->putProductVariants($client, $requestData, $settingId, $settingName, $osProductId);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertTrue($responseData['error']);
        $this->assertEquals(404, $responseData['errorCode']);
        $this->assertEquals('Setting #'.$settingId.' is not found', $responseData['errorMessage']);
    }

    public function testDeleteProductVariant()
    {
        $client = static::createClient();
        $variant = $this->getAvailableVariant($client);

        $client->request(
            'DELETE',
            '/rest/v1/shop/products/' . $variant->getProduct()->getId() . '/variants/' . $variant->getId()
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @param Client $client
     * @return ProductVariant
     */
    private function getAvailableVariant(Client $client)
    {
        $variant = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:ProductVariant')
                          ->findOneBy(['master' => 0],['id'=>'desc']);
        $this->assertNotEmpty($variant);
        return $variant;
    }

    /**
     * @param Client $client
     * @return Product $product
     */
    private function getAvailableProduct(Client $client)
    {
        $product = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:Product')
            ->findBy(['deletedAt'=> null])[0];
        $this->assertNotEmpty($product);
        return $product;
    }

    /**
     * @param $settingsData
     */
    private function changeAllKeys(&$settingsData)
    {
        foreach (array_keys($settingsData) as $settingKey) {
            $setting = $settingsData[$settingKey];

            if (is_bool($setting)) {
                $setting = 1 - $setting;
            } elseif (!is_array($setting)) {
                $setting .= time();
            }

            $settingsData[$settingKey] = $setting;
        }
    }

    /**
     * @param Client $client
     * @return OSProduct
     */
    private function getAvailableOSProduct(Client $client)
    {
        $osProducts = $client->getContainer()->get('doctrine')
                             ->getRepository('AppOpenSolutionBundle:OSProduct')
                             ->findBy([],null,10);

        return $osProducts[mt_rand(0,9)];
    }

    /**
     * @param Client $client
     * @return mixed
     */
    private function getAvailableSetting($client)
    {
        return $client->getContainer()->get('doctrine')
                      ->getRepository('AppCoreBundle:ProductVariantSettings')
                      ->findBy([], null, 2)[mt_rand(0, 1)];
    }

    /**
     * @param Client $client
     * @param $requestData
     * @param $settingId
     * @param $settingName
     * @param $osProductId
     * @return array
     */
    private function putProductVariants(Client $client, &$requestData, $settingId, $settingName, $osProductId)
    {
        $requestData = json_decode(file_get_contents(__DIR__ . '/files/putProductVariant.json'), true);
        $requestData['settings_template']['id'] = $settingId;
        $requestData['settings_template']['name'] = $settingName;
        $requestData['os_product_id'] = $osProductId;

        $this->changeAllKeys($requestData['settings']);
        $this->changeAllKeys($requestData['settings']['availability']);
        $variant = $this->getAvailableVariant($client);

        $client->request(
            'PUT',
            '/rest/v1/shop/products/' . $variant->getProduct()->getId() . '/variants/' . $variant->getId(),
            $requestData
        );

        return json_decode($client->getResponse()->getContent(), true);
    }
}