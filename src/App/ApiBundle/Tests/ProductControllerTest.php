<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 14.09.15
 */

namespace App\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->getContainer()->get('session')->set('currentOrder', [
            'type' => 'hotel'
        ]);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        $client = self::createClient();

        $products = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:Product')
            ->findBy([],null,2);

        $urls = [];

        foreach ($products as $product) {
            $urls[] = ['/rest/v1/shop/products/' . $product->getId() . '.json'];
        }

        $this->assertNotEmpty($urls);

        return $urls;
    }

    public function testGetSplittableProductsAction()
    {
       $client = self::createClient();

        $variant = $this->getAvailableVariant($client);

        $client->request('GET', '/rest/v1/shop/splittables/'.$variant->getId().'/products.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }



    /**
     * @param Client $client
     * @return ProductVariant
     */
    private function getAvailableVariant(Client $client)
    {
        $variant = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:ProductVariant')
            ->findOneBy(['defaultSettings' => $this->getStorVariantSetting($client)]);
        $this->assertNotEmpty($variant);
        return $variant;
    }


    /**
     * @param Client $client
     * @return ProductVariant
     */
    private function getStorVariantSetting(Client $client)
    {
        $setting = $client->getContainer()->get('doctrine')->getRepository('AppCoreBundle:ProductVariantSettings')
            ->findOneBy(['name' => 'Klassisk - Stor']);
        $this->assertNotEmpty($setting);
        return $setting;
    }


}