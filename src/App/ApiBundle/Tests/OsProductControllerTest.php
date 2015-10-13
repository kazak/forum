<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 15.09.15
 */

namespace App\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OsProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        return [
            ['/rest/v1/shop/osproducts.json?query=Stor']
        ];
    }

    public function testIfCorrectResponse()
    {
        $client = $this->getClientByQuery(['query' => 'Stor']);

        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $json);
        $this->assertGreaterThan(0, count($json['data']));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $json['errorCode']);
    }

    public function testIfCorrectResponseById()
    {
        $client = $this->getClientByQuery(['query' => '10001']);

        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $json);
        $this->assertGreaterThan(0, count($json['data']));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $json['errorCode']);
    }

    public function testIncorrectResponse()
    {
        $client = $this->getClientByQuery(['fakeparam' => 'Pepperoni']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $json['errorCode']);
        $this->assertEquals('Query parameter "query" is empty', $json['errorMessage']);
    }

    public function testQueryLength()
    {
        $client = $this->getClientByQuery(['query' => 'Pepperoniidasdasdas;lk;lkdasl;dksal;kl;dkas;lkdwqekwqel;qwk;ldksad;lskdas;k']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $json['errorCode']);
        $this->assertEquals("Query parameter query value 'Pepperoniidasdasdas;lk;lkdasl;dksal;kl;dkas;lkdwqekwqel;qwk;ldksad;lskdas;k' violated a constraint (Query parameter value 'Pepperoniidasdasdas;lk;lkdasl;dksal;kl;dkas;lkdwqekwqel;qwk;ldksad;lskdas;k', does not match requirements '.{0,40}')", $json['errorMessage']);
    }

    public function testProductNotFound()
    {
        $client = $this->getClientByQuery(['query' => 'Some fake product']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(404, $json['errorCode']);
    }

    /**
     * @param array $params
     * @return Client
     */
    private function getClientByQuery(array $params)
    {
        $client = static::createClient();
        $url = '/rest/v1/shop/osproducts';
        $client->request('GET', $url, $params);
        return $client;
    }
}