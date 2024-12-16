<?php

namespace App\Tests\Unit\Domain\Entity\Collection;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProductCollectionTest extends TestCase
{
    public function testAddAndListItems(): void
    {
        $collection = new FruitCollection();

        $product = new Product(1, 'Banana', ProductType::from('fruit'), Weight::from(1000, 'g'));
        $collection->add($product);

        $items = $collection->list();
        $this->assertCount(1, $items);
        $this->assertSame($product, $items[1]);
    }

    public function testRemoveItem(): void
    {
        $collection = new FruitCollection();

        $product = new Product(2, 'Apple', ProductType::from('fruit'), Weight::from(500, 'g'));
        $collection->add($product);

        $this->assertCount(1, $collection->list());

        $collection->remove($product);

        $this->assertCount(0, $collection->list());
    }

    public function testAddInvalidProductThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only fruits can be added to FruitCollection.');

        $collection = new FruitCollection();
        $vegetable = new Product(3, 'Carrot', ProductType::from('vegetable'), Weight::from(200, 'g'));

        $collection->add($vegetable);
    }
}