<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\ProductUnit;
use PHPUnit\Framework\TestCase;

class ProductUnitTest extends TestCase
{
    public function testUnknownProductUnit()
    {
        $this->expectException(\ValueError::class);
        $unit = ProductUnit::from('unknown');
    }

    public function testNotEqualUnits()
    {
        $gram = ProductUnit::from('g');
        $kilogram = ProductUnit::from('kg');

        $this->assertFalse($gram->equals($kilogram));
    }
}
