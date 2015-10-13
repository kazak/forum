<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 02.09.15
 */

namespace App\ApiBundle\Tests;

use App\ApiBundle\Tests\Customer\SmsServiceMock;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{
    public function testGetCorrectCustomer()
    {
        $client = static::createClient();
        $this->loginCustomerByPhone($client);

        $client->request('GET', '/rest/v1/user/customer.json');

        $response = $this->getContent($client);

        $this->assertEquals('os-customer-1@local.local', $response['data']['email']);

        $this->assertArrayHasKey('isBonusCustomer', $response['data']['settings']);
        $this->assertArrayHasKey('isBonusCustomer', $response['data']);

        $this->assertArrayHasKey('acceptNewsletters', $response['data']['settings']);
        $this->assertArrayHasKey('acceptNewsletters', $response['data']);
        $this->assertEquals('1', $response['data']['acceptNewsletters']);
    }

    public function testPostUserCustomer()
    {
        $client = static::createClient();
        $client->getContainer()->set('app_core_sms', new SmsServiceMock($client->getContainer()));
        $customer = json_decode(file_get_contents(__DIR__ . '/files/postUserCustomer.json'), true);
        $client->request('POST', '/rest/v1/user/customer.json', $customer);
        echo $client->getResponse()->getContent();
    }

    private function loginCustomerByPhone(Client $client)
    {
        $client->request('POST', '/rest/v1/user/login.json', ['phone' => '1239239032', 'password' => '1234']);
        $response = $this->getContent($client);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertEquals('Success', $response['errorMessage']);
    }

    /**
     * @param $client
     * @return mixed
     */
    private function getContent($client)
    {
        return json_decode($client->getResponse()->getContent(), true);
    }
}