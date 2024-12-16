<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Dto\ProductFilter;
use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\ProductCollectionManager;
use App\Domain\Service\ProductQueryService;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProductQueryServiceTest extends TestCase
{
    private ProductCollectionManager $collectionManager;
    private ProductRepositoryInterface $productRepository;
    private ProductQueryService $queryService;

    protected function setUp(): void
    {
        $this->collectionManager = $this->createMock(ProductCollectionManager::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->queryService = new ProductQueryService($this->collectionManager, $this->productRepository);
    }

    public function testLoadFilteredCollections(): void
    {
        $filter = new ProductFilter(name: 'apple', minWeight: 500);

        $fruit = new Product(1, 'Apple', ProductType::from('fruit'), Weight::from(500, 'g'));
        $vegetable = new Product(2, 'Carrot', ProductType::from('vegetable'), Weight::from(1000, 'g'));

        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findByTypeAndFilter')
            ->withConsecutive(
                [ProductType::fruit, $filter],
                [ProductType::vegetable, $filter]
            )
            ->willReturnOnConsecutiveCalls([$fruit], [$vegetable]);

        $this->collectionManager
            ->expects($this->exactly(2))
            ->method('addItem')
            ->withConsecutive([$fruit], [$vegetable]);

        $this->collectionManager
            ->expects($this->once())
            ->method('getCollections')
            ->willReturn([
                'fruits' => new FruitCollection([$fruit]),
                'vegetables' => new VegetableCollection([$vegetable]),
            ]);

        $collections = $this->queryService->loadFilteredCollections($filter);

        $this->assertArrayHasKey('fruits', $collections);
        $this->assertArrayHasKey('vegetables', $collections);
        $this->assertInstanceOf(FruitCollection::class, $collections['fruits']);
        $this->assertInstanceOf(VegetableCollection::class, $collections['vegetables']);
    }

    public function testLoadFilteredCollectionsWithEmptyFilter(): void
    {
        $filter = new ProductFilter();

        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findByTypeAndFilter')
            ->withConsecutive(
                [ProductType::fruit, $filter],
                [ProductType::vegetable, $filter]
            )
            ->willReturnOnConsecutiveCalls([], []);

        $this->collectionManager
            ->expects($this->never())
            ->method('addItem');

        $this->collectionManager
            ->expects($this->once())
            ->method('getCollections')
            ->willReturn([
                'fruits' => new FruitCollection(),
                'vegetables' => new VegetableCollection(),
            ]);

        $collections = $this->queryService->loadFilteredCollections($filter);

        $this->assertArrayHasKey('fruits', $collections);
        $this->assertArrayHasKey('vegetables', $collections);
        $this->assertInstanceOf(FruitCollection::class, $collections['fruits']);
        $this->assertInstanceOf(VegetableCollection::class, $collections['vegetables']);
        $this->assertCount(0, $collections['fruits']->list());
        $this->assertCount(0, $collections['vegetables']->list());
    }
}
