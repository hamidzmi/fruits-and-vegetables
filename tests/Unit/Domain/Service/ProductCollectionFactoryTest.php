<?php

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Service\ProductCollectionFactory;
use App\Domain\ValueObject\ProductType;
use PHPUnit\Framework\TestCase;

class ProductCollectionFactoryTest extends TestCase
{
    private ProductCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ProductCollectionFactory();
    }

    public function testMakeCreatesFruitCollection(): void
    {
        $collection = $this->factory->make(ProductType::fruit);

        $this->assertInstanceOf(FruitCollection::class, $collection);
        $this->assertInstanceOf(ProductCollection::class, $collection); // Ensures it implements ProductCollection
    }

    public function testMakeCreatesVegetableCollection(): void
    {
        $collection = $this->factory->make(ProductType::vegetable);

        $this->assertInstanceOf(VegetableCollection::class, $collection);
        $this->assertInstanceOf(ProductCollection::class, $collection); // Ensures it implements ProductCollection
    }

    public function testMakeThrowsExceptionForUnknownType(): void
    {
        $this->expectException(\ValueError::class);

        $this->factory->make(ProductType::from('unknown'));
    }
}
