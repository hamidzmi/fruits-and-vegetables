<?php

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Dto\AddItemToCollectionDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\UseCase\AddItemToCollectionUseCase;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class AddItemToCollectionUseCaseTest extends TestCase
{
    private CollectionService $collectionService;
    private AddItemToCollectionUseCase $useCase;

    protected function setUp(): void
    {
        // Mock the CollectionService
        $this->collectionService = $this->createMock(CollectionService::class);

        // Instantiate the use case with the mocked service
        $this->useCase = new AddItemToCollectionUseCase($this->collectionService);
    }

    public function testExecuteAddsProductToCollection(): void
    {
        // Arrange: Input DTO and type
        $type = 'fruit';
        $dto = new AddItemToCollectionDto([
            'id' => '1',
            'name' => 'Apple',
            'quantity' => 500,
            'unit' => 'g'
        ]);

        // Expected product entity
        $expectedProduct = new Product(
            '1',
            'Apple',
            ProductType::from($type),
            Weight::from(500, 'g')
        );

        // Expectation: CollectionService::addItemToCollection will be called once
        $this->collectionService->expects($this->once())
            ->method('addItemToCollection')
            ->with(
                ProductType::from($type),
                $this->equalTo($expectedProduct)
            );

        // Act: Execute the use case
        $this->useCase->execute($type, $dto);

        // Assert: No exception is thrown, and expectations are fulfilled
        $this->addToAssertionCount(1); // Ensures the mock expectations were met
    }

    public function testExecuteWithInvalidTypeThrowsValueError(): void
    {
        $this->expectException(\ValueError::class);

        // Arrange: Invalid product type
        $type = 'invalid_type';
        $dto = new AddItemToCollectionDto([
            'id' => '1',
            'name' => 'Invalid Product',
            'quantity' => 500,
            'unit' => 'g'
        ]);

        // Act: Execute the use case (should throw ValueError)
        $this->useCase->execute($type, $dto);
    }

    public function testExecuteHandlesDifferentUnits(): void
    {
        // Arrange: Input DTO with "kilograms"
        $type = 'vegetable';
        $dto = new AddItemToCollectionDto([
            'id' => '2',
            'name' => 'Carrot',
            'quantity' => 2,
            'unit' => 'kg'
        ]);

        // Expected product entity with weight in grams
        $expectedProduct = new Product(
            '2',
            'Carrot',
            ProductType::from($type),
            Weight::from(2000, 'g') // Converted to grams internally
        );

        // Expectation: CollectionService::addItemToCollection will be called
        $this->collectionService->expects($this->once())
            ->method('addItemToCollection')
            ->with(
                ProductType::from($type),
                $this->equalTo($expectedProduct)
            );

        // Act: Execute the use case
        $this->useCase->execute($type, $dto);

        // Assert: No exception is thrown, and expectations are fulfilled
        $this->addToAssertionCount(1);
    }
}
