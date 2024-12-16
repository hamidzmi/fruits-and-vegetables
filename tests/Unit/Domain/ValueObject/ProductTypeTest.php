<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\ProductType;
use PHPUnit\Framework\TestCase;

class ProductTypeTest extends TestCase
{
    public function testNotEqualProductTypes(): void
    {
        $fruitType = ProductType::fruit;
        $vegetableType = ProductType::vegetable;

        $this->assertFalse($fruitType->equals($vegetableType));
    }

    public function testUnknownProductType()
    {
        $this->expectException(\ValueError::class);
        $type = ProductType::from('unknown');
    }
}
