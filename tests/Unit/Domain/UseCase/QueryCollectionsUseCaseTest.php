<?php

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\UseCase\QueryCollectionsUseCase;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class QueryCollectionsUseCaseTest extends TestCase
{
    private CollectionService $collectionService;
    private QueryCollectionsUseCase $useCase;

    protected function setUp(): void
    {
        // Mock the CollectionService
        $this->collectionService = $this->createMock(CollectionService::class);

        // Instantiate the use case with the mocked service
        $this->useCase = new QueryCollectionsUseCase($this->collectionService);
    }

    public function testExecuteWithoutFiltersReturnsFullList(): void
    {
        // Arrange: ProductType and mock products
        $type = ProductType::fruit;
        $product1 = new Product('1', 'Apple', $type, Weight::from(500, 'g'));
        $product2 = new Product('2', 'Banana', $type, Weight::from(300, 'g'));

        // Mock CollectionService to return a list of products
        $this->collectionService->expects($this->once())
            ->method('listItemsFromCollection')
            ->with($type)
            ->willReturn([$product1, $product2]);

        $filterDto = new ProductFilterDto(); // Empty filters

        // Act: Execute the use case
        $result = $this->useCase->execute($type, $filterDto);

        // Assert: Verify serialized output
        $this->assertCount(2, $result);
        $this->assertEquals([
            'id' => '1',
            'name' => 'Apple',
            'type' => 'fruit',
            'weight' => 500
        ], $result[0]);
        $this->assertEquals([
            'id' => '2',
            'name' => 'Banana',
            'type' => 'fruit',
            'weight' => 300
        ], $result[1]);
    }

    public function testExecuteWithFiltersReturnsFilteredResults(): void
    {
        // Arrange: ProductType, mock products, and a filter
        $type = ProductType::vegetable;
        $product1 = new Product('1', 'Carrot', $type, Weight::from(200, 'g'));
        $product2 = new Product('2', 'Potato', $type, Weight::from(500, 'g'));

        // Mock CollectionService to return filtered products
        $this->collectionService->expects($this->once())
            ->method('searchItemsInCollection')
            ->with($type, $this->isInstanceOf(ProductFilterDto::class))
            ->willReturn([$product1]);

        $filterDto = new ProductFilterDto('Carrot', null, null);

        // Act: Execute the use case
        $result = $this->useCase->execute($type, $filterDto);

        // Assert: Verify serialized output contains only the filtered product
        $this->assertCount(1, $result);
        $this->assertEquals([
            'id' => '1',
            'name' => 'Carrot',
            'type' => 'vegetable',
            'weight' => 200
        ], $result[0]);
    }

    public function testExecuteWithEmptyResultReturnsEmptyArray(): void
    {
        // Arrange: ProductType and empty result from service
        $type = ProductType::fruit;
        $filterDto = new ProductFilterDto();

        $this->collectionService->expects($this->once())
            ->method('listItemsFromCollection')
            ->with($type)
            ->willReturn([]);

        // Act: Execute the use case
        $result = $this->useCase->execute($type, $filterDto);

        // Assert: Verify that the result is an empty array
        $this->assertEmpty($result);
    }
}
