<?php

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Dto\ProcessRequestDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\UseCase\ProcessRequestUseCase;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProcessRequestUseCaseTest extends TestCase
{
    private CollectionService $collectionService;
    private ProcessRequestUseCase $useCase;

    protected function setUp(): void
    {
        // Mock the CollectionService
        $this->collectionService = $this->createMock(CollectionService::class);

        // Instantiate the use case with the mocked service
        $this->useCase = new ProcessRequestUseCase($this->collectionService);
    }

    public function testExecuteProcessesProductsAndReturnsCollections(): void
    {
        // Arrange: Mock input DTO
        $dto = new ProcessRequestDto([
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => 500,
                'unit' => 'g'
            ],
            [
                'id' => '2',
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 300,
                'unit' => 'g'
            ]
        ]);

        // Create expected products
        $product1 = new Product('1', 'Apple', ProductType::fruit, Weight::from(500, 'g'));
        $product2 = new Product('2', 'Carrot', ProductType::vegetable, Weight::from(300, 'g'));

        // Expectations: addItemToCollection will be called twice
        $this->collectionService->expects($this->exactly(2))
            ->method('addItemToCollection')
            ->withConsecutive(
                [ProductType::fruit, $product1],
                [ProductType::vegetable, $product2]
            );

        // Expectations: getCollection for fruits and vegetables
        $this->collectionService->expects($this->exactly(2))
            ->method('getCollection')
            ->withConsecutive(
                [ProductType::fruit],
                [ProductType::vegetable]
            )
            ->willReturnOnConsecutiveCalls(
                $this->mockCollection([['id' => '1', 'name' => 'Apple', 'type' => 'fruit', 'weight' => 500]]),
                $this->mockCollection([['id' => '2', 'name' => 'Carrot', 'type' => 'vegetable', 'weight' => 300]])
            );

        // Act: Execute the use case
        $result = $this->useCase->execute($dto);

        // Assert: Check the output structure
        $this->assertArrayHasKey('fruits', $result);
        $this->assertArrayHasKey('vegetables', $result);

        $this->assertCount(1, $result['fruits']);
        $this->assertEquals('Apple', $result['fruits'][0]['name']);
        $this->assertCount(1, $result['vegetables']);
        $this->assertEquals('Carrot', $result['vegetables'][0]['name']);
    }

    public function testExecuteWithEmptyInputReturnsEmptyCollections(): void
    {
        // Arrange: Empty DTO
        $dto = new ProcessRequestDto([]);

        // Expectations: getCollection for fruits and vegetables
        $this->collectionService->expects($this->exactly(2))
            ->method('getCollection')
            ->withConsecutive(
                [ProductType::fruit],
                [ProductType::vegetable]
            )
            ->willReturnOnConsecutiveCalls(
                $this->mockCollection([]),
                $this->mockCollection([])
            );

        // Act: Execute the use case
        $result = $this->useCase->execute($dto);

        // Assert: Check that both collections are empty
        $this->assertArrayHasKey('fruits', $result);
        $this->assertArrayHasKey('vegetables', $result);

        $this->assertEmpty($result['fruits']);
        $this->assertEmpty($result['vegetables']);
    }

    private function mockCollection(array $data)
    {
        $collection = $this->createMock(\App\Domain\Entity\Collection\ProductCollection::class);
        $collection->method('toArray')->willReturn($data);

        return $collection;
    }
}
