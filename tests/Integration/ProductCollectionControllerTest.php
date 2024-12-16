<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductCollectionControllerTest extends WebTestCase
{
    public function testGetCollectionsWithoutFilters(): void
    {
        $client = static::createClient();

        // Send a GET request to the endpoint without filters
        $client->request('GET', '/api/collections');

        // Assert that the response is successful
        $this->assertResponseIsSuccessful();

        // Assert the JSON structure
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('fruits', $responseData);
        $this->assertArrayHasKey('vegetables', $responseData);

        // Assert fruits and vegetables are arrays
        $this->assertIsArray($responseData['fruits']);
        $this->assertIsArray($responseData['vegetables']);
    }

    public function testGetCollectionsWithFilters(): void
    {
        $client = static::createClient();

        // Send a GET request to the endpoint with filters
        $client->request('GET', '/api/collections', [
            'name' => 'apple',
            'minWeight' => 500,
            'maxWeight' => 2000,
        ]);

        // Assert that the response is successful
        $this->assertResponseIsSuccessful();

        // Assert the JSON structure
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('fruits', $responseData);
        $this->assertArrayHasKey('vegetables', $responseData);

        // Assert fruits and vegetables are arrays
        $this->assertIsArray($responseData['fruits']);
        $this->assertIsArray($responseData['vegetables']);

        // Further assertions on filtered data
        foreach ($responseData['fruits'] as $fruit) {
            $this->assertStringContainsString('apple', strtolower($fruit['name']));
            $this->assertGreaterThanOrEqual(500, $fruit['weight']);
            $this->assertLessThanOrEqual(2000, $fruit['weight']);
        }

        foreach ($responseData['vegetables'] as $vegetable) {
            $this->assertGreaterThanOrEqual(500, $vegetable['weight']);
            $this->assertLessThanOrEqual(2000, $vegetable['weight']);
        }
    }
}
