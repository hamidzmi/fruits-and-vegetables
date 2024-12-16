<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductProcessorControllerTest extends WebTestCase
{
    public function testProcessFile(): void
    {
        $client = static::createClient();

        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => 1000,
                'unit' => 'g',
            ],
            [
                'id' => 2,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => 'g',
            ],
        ]);

        $client->request('POST', '/api/process', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('fruits', $responseContent);
        $this->assertArrayHasKey('vegetables', $responseContent);
    }
}