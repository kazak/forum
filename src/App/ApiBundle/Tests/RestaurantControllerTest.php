<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 02.09.15
 */

namespace App\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RestaurantControllerTest extends WebTestCase
{
    public function testThreeRestaurantReturnIfPostCodeExists()
    {
        $postCode = '1155';
        $data = $this->searchRestaurantByQuery($postCode, 3);
        $this->assertEquals("Dolly Dimple's tveita", $data[0]['name']);
    }

    public function testOneRestaurantReturnIfAddressExists()
    {
        $postCode = 'Industrigata 36 0357';
        $this->searchRestaurantByQuery($postCode);
    }

    public function testOneRestaurantReturnIfIdExists()
    {
        $client = static::createClient();
        $client->request('GET', '/rest/v1/restaurants.json?id=36000');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, count($response['data']), print_r($response, true));
    }

    public function testOneRestaurantReturnIfCityExists()
    {
        $city = 'Oslo';
        $this->searchRestaurantByQuery($city);
    }

    public function testBadRequest()
    {
        $client = static::createClient();
        foreach (
            [
                '/rest/v1/restaurants.json?long2=10.74835&lat=59.91295',
                '/rest/v1/restaurants.json?query2=1'
            ]
            as
            $url
        ) {
            $client->request('GET', $url);
            $response = json_decode($client->getResponse()->getContent(), true);

            $this->assertEquals(400, $client->getResponse()->getStatusCode());
            $this->assertEquals(400, $response['errorCode']);
            $this->assertEquals('Bad request', $response['errorMessage']);
        }
    }

    public function testNoContent()
    {
        $client = static::createClient();
        foreach (
            [
                '/rest/v1/restaurants.json?long=100.74835&lat=59.91295',
                '/rest/v1/restaurants.json?id=1001',
                '/rest/v1/restaurants.json?query=100001'
            ]
            as
            $url
        ) {
            $client->request('GET', $url);
            $response = json_decode($client->getResponse()->getContent(), true);

            $this->assertEquals(404, $client->getResponse()->getStatusCode(), $response['errorMessage']);
            $this->assertEquals(404, $response['errorCode']);
            $this->assertEquals('No content', $response['errorMessage']);
        }
    }

    public function testThreeRestaurantsIfLongAndLatExist()
    {
        $client = static::createClient();
        $client->request('GET', '/rest/v1/restaurants.json?long=10.74835&lat=59.91295');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(3, count($response['data']));
    }

    public function testGetOpeningHours()
    {
        $client = static::createClient();
        $client->request('GET', '/rest/v1/restaurants/opening_hours.json', [
            'restaurant_id' => 1001,
            'service' => 'take-away',
            'days' => '3'
        ]);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(3, count($response['data']));

        $this->checkOpeningHour($response['data'][1], time() + 3600 * 24);
    }

    /**
     * @param $responseData
     * @param $tomorrowTimestamp
     * @param string $openingTime
     * @param string $closingTime
     */
    private function checkOpeningHour($responseData, $tomorrowTimestamp, $openingTime = '09:00:00', $closingTime = '23:00:00')
    {
        $actualTomorrowOpeningHours = $responseData;
        $expectedTomorrowOpeningHours = [
            'date' => date('Y-m-d', $tomorrowTimestamp),
            'opening_time' => $openingTime,
            'closing_time' => $closingTime
        ];

        $this->assertEquals($expectedTomorrowOpeningHours, $actualTomorrowOpeningHours);
    }

    /**
     * @param $query
     * @param int $count
     * @return array
     */
    private function searchRestaurantByQuery($query, $count = 3)
    {
        $client = static::createClient();
        $client->request('GET', '/rest/v1/restaurants.json', ['query' => $query]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($count, count($response['data']), print_r($response, true));
        return $response['data'];
    }
}