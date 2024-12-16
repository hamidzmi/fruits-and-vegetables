<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Weight;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCreateWeight(): void
    {
        $weight = Weight::from(1000, 'g');
        $this->assertEquals(1000, $weight->toGrams());
    }

    public function testConvertWeight(): void
    {
        $weight = Weight::from(2, 'kg');
        $convertedWeight = $weight->toGrams();

        $this->assertEquals(2000, $convertedWeight);
    }

    public function testInvalidWeight(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Weight::from(-100, 'kg');
    }

    public function testInvalidUnit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Weight::from(1000, 'unknown-unit');
    }
}
