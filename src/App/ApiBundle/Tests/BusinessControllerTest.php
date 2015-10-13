<?php

namespace App\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest.
 */
class BusinessControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = static::createClient();

        $client->request('GET', '/rest/v1/business.json?query=abrakadabra');
        $this->checkBadRequest($client,"Query parameter query value 'abrakadabra' violated a constraint (Query parameter value 'abrakadabra', does not match requirements '\d+')");
        $client->request('GET', '/rest/v1/business.json?id=1');
        $this->checkBadRequest($client,'Query parameter "query" is empty');
    }

    public function testCorporateCustomerNotFound()
    {
        $client = static::createClient();

        $customerToSearch = 666;
        $client->request('GET', '/rest/v1/business.json?query='. $customerToSearch);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('error', $response, print_r($response, true));
        $this->assertEquals($response['error'], true);
        $this->assertArrayHasKey('errorCode', $response, print_r($response, true));
        $this->assertEquals($response['errorCode'], 404);
        $this->assertArrayHasKey('errorMessage', $response, print_r($response, true));
        $this->assertEquals($response['errorMessage'], 'Business not found');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

    }

    public function testCorporateCustomerFound()
    {
        $client = static::createClient();

        $customerToSearch = 8;
        $client->request('GET', '/rest/v1/business.json?query='. $customerToSearch);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $response['errorCode']);
        $this->assertFalse($response['error']);

        $data = $response['data'];
        $this->assertEquals('Norse Digital AS', $data['companyName']);
        $this->assertEquals($customerToSearch, $data['osCustomerId']);
        $this->assertTrue($data['canPayWithInvoice']);
        $this->assertEquals('OS Invoice Address', $data['invoiceAddress']);
        $this->assertEquals('1234', $data['invoicePostCode']);
        $this->assertEquals('OS Invoice CO Address', $data['invoicePostOffice']);
        $this->assertEquals('OS Invoice Country', $data['invoiceCountry']);

        $this->assertEquals(3, count($data['references']));

        $this->assertEquals([
            "id" => "1",
            "order" => "1",
            "label" => "Project id",
            "info" => "The project id should be on the format XX-XXXX.",
            "predefined" => "",
            "content" => ""
        ], $data['references'][0]);
    }

    public function testCorporateCustomerWithoutReferencesFound()
    {
        $client = static::createClient();

        $customerToSearch = 9;
        $client->request('GET', '/rest/v1/business.json?query='. $customerToSearch);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $response['errorCode']);
        $this->assertFalse($response['error']);

        $data = $response['data'];
        $this->assertEquals('Norse Digital AS', $data['companyName']);
        $this->assertEquals($customerToSearch, $data['osCustomerId']);
        $this->assertTrue($data['canPayWithInvoice']);
        $this->assertEquals('OS Invoice Address', $data['invoiceAddress']);
        $this->assertEquals('1234', $data['invoicePostCode']);
        $this->assertEquals('OS Invoice CO Address', $data['invoicePostOffice']);
        $this->assertEquals('OS Invoice Country', $data['invoiceCountry']);
    }

    /**
     * @param Client $client
     * @param $errorMessage
     */
    private function checkBadRequest($client, $errorMessage)
    {
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('errorMessage', $response, print_r($response, true));
        $this->assertEquals($errorMessage, $response['errorMessage']);
        $this->assertArrayHasKey('error', $response, print_r($response, true));
        $this->assertTrue($response['error']);
        $this->assertArrayHasKey('errorCode', $response, print_r($response, true));
        $this->assertEquals(400, $response['errorCode']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

}
