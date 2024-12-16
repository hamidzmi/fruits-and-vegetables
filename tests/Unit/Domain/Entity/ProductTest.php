<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductInitialization(): void
    {
        $id = 1;
        $name = 'Banana';
        $type = ProductType::from('fruit');
        $weight = Weight::from(1000, 'g');

        $product = new Product($id, $name, $type, $weight);

        $this->assertEquals($id, $product->getId());
        $this->assertEquals($name, $product->getName());
        $this->assertSame($type, $product->getType());
        $this->assertSame($weight, $product->getWeight());
    }

    public function testProductTypeAndWeightValues(): void
    {
        $product = new Product(
            2,
            'Carrot',
            ProductType::from('vegetable'),
            Weight::from(500, 'g')
        );

        $this->assertEquals('vegetable', $product->getType()->value);
        $this->assertEquals(500, $product->getWeight()->toGrams());
    }
}