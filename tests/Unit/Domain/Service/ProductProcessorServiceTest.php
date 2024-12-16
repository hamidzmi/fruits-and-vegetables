<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\ProductCollectionManager;
use App\Domain\Service\ProductProcessorService;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProductProcessorServiceTest extends TestCase
{
    private ProductCollectionManager $productCollectionManager;
    private ProductRepositoryInterface $productRepository;
    private ProductProcessorService $productProcessorService;

    protected function setUp(): void
    {
        $this->productCollectionManager = $this->createMock(ProductCollectionManager::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);

        $this->productProcessorService = new ProductProcessorService(
            $this->productCollectionManager,
            $this->productRepository
        );
    }

    public function testProcessAddsProductsToCollections(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 500, 'unit' => 'g'],
            ['id' => 2, 'name' => 'Carrot', 'type' => 'vegetable', 'quantity' => 1000, 'unit' => 'g'],
        ];

        $fruit = new Product(1, 'Apple', ProductType::from('fruit'), Weight::from(500, 'g'));
        $vegetable = new Product(2, 'Carrot', ProductType::from('vegetable'), Weight::from(1000, 'g'));

        $this->productCollectionManager
            ->expects($this->exactly(2))
            ->method('addItem')
            ->withConsecutive([$fruit], [$vegetable]);

        $fruitCollection = new FruitCollection();
        $vegetableCollection = new VegetableCollection();

        $this->productCollectionManager
            ->method('getCollections')
            ->willReturn([
                'fruits' => $fruitCollection,
                'vegetables' => $vegetableCollection,
            ]);

        $collections = $this->productProcessorService->process($data);

        $this->assertArrayHasKey('fruits', $collections);
        $this->assertArrayHasKey('vegetables', $collections);
        $this->assertInstanceOf(FruitCollection::class, $collections['fruits']);
        $this->assertInstanceOf(VegetableCollection::class, $collections['vegetables']);
    }

    public function testStoreCollectionCallsRepositorySave(): void
    {
        $collection = new FruitCollection();

        $this->productRepository
            ->expects($this->once())
            ->method('saveCollection')
            ->with($collection);

        $this->productProcessorService->storeCollection($collection);
    }
}