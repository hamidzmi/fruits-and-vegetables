<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\CollectionService;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class CollectionServiceTest extends TestCase
{
    private ProductRepositoryInterface $repository;
    private CollectionService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
        $this->service = new CollectionService($this->repository);
    }

    public function testGetCollectionWithNoProducts(): void
    {
        $type = ProductType::fruit;

        $this->repository->expects($this->once())
            ->method('findByType')
            ->with($type)
            ->willReturn([]);

        $collection = $this->service->getCollection($type);

        $this->assertInstanceOf(FruitCollection::class, $collection);
        $this->assertEmpty($collection->list());
    }

    public function testGetCollectionWithProducts(): void
    {
        $type = ProductType::fruit;
        $product1 = new Product('1', 'Apple', ProductType::fruit, Weight::from(500, 'g'));
        $product2 = new Product('2', 'Banana', ProductType::fruit, Weight::from(300, 'g'));

        // Repository mock returns products
        $this->repository->expects($this->once())
            ->method('findByType')
            ->with($type)
            ->willReturn([$product1, $product2]);

        $collection = $this->service->getCollection($type);

        $this->assertInstanceOf(FruitCollection::class, $collection);
        $this->assertCount(2, $collection->list());
    }

    public function testAddItemToCollection(): void
    {
        $type = ProductType::fruit;
        $product = new Product('1', 'Apple', ProductType::fruit, Weight::from(500, 'g'));

        $this->repository->expects($this->once())
            ->method('saveCollection')
            ->with(
                $this->isInstanceOf(ProductCollection::class),
                $type
            );

        $this->service->addItemToCollection($type, $product);
    }

    public function testListItemsFromCollection(): void
    {
        $type = ProductType::vegetable;
        $product = new Product('1', 'Carrot', ProductType::vegetable, Weight::from(200, 'g'));

        $this->repository->expects($this->once())
            ->method('findByType')
            ->with($type)
            ->willReturn([$product]);

        $items = $this->service->listItemsFromCollection($type);

        $this->assertCount(1, $items);
        $this->assertEquals('Carrot', $items[0]->getName());
    }

    public function testSearchItemsInCollection(): void
    {
        $type = ProductType::fruit;
        $product1 = new Product('1', 'Carrot', ProductType::fruit, Weight::from(200, 'g'));
        $product2 = new Product('2', 'Potato', ProductType::fruit, Weight::from(500, 'g'));

        $collection = new FruitCollection();
        $collection->add($product1);
        $collection->add($product2);

        $filterDto = new ProductFilterDto('Carrot', null, null);

        $this->repository->expects($this->once())
            ->method('findByType')
            ->with($type)
            ->willReturn([$product1, $product2]);

        $results = $this->service->searchItemsInCollection($type, $filterDto);

        $this->assertCount(1, $results);
        $this->assertEquals('Carrot', $results[0]->getName());
    }
}
