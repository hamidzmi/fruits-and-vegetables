<?php

namespace App\Tests\Unit\Domain\Entity\Collection;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class FruitCollectionTest extends TestCase
{
    public function testAddFruit(): void
    {
        $collection = new FruitCollection();

        $fruit = new Product(1, 'Banana', ProductType::from('fruit'), Weight::from(500, 'g'));
        $collection->add($fruit);

        $this->assertCount(1, $collection->list());
        $this->assertSame($fruit, $collection->list()[1]);
    }

    public function testAddNonFruitThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only fruits can be added to FruitCollection.');

        $collection = new FruitCollection();

        $vegetable = new Product(2, 'Carrot', ProductType::from('vegetable'), Weight::from(1000, 'g'));
        $collection->add($vegetable);
    }
}