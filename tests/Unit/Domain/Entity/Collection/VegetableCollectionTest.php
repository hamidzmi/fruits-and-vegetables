<?php

namespace App\Tests\Unit\Domain\Entity\Collection;

use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class VegetableCollectionTest extends TestCase
{
    public function testAddVegetable(): void
    {
        $collection = new VegetableCollection();

        $vegetable = new Product(1, 'Carrot', ProductType::from('vegetable'), Weight::from(1000, 'g'));
        $collection->add($vegetable);

        $this->assertCount(1, $collection->list());
        $this->assertSame($vegetable, $collection->list()[1]);
    }

    public function testAddNonVegetableThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only vegetables can be added to VegetableCollection.');

        $collection = new VegetableCollection();

        $fruit = new Product(2, 'Banana', ProductType::from('fruit'), Weight::from(500, 'g'));
        $collection->add($fruit);
    }
}