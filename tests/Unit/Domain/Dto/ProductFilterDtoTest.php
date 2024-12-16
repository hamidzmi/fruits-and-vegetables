<?php

namespace App\Tests\Unit\Domain\Dto;

use App\Domain\Dto\ProductFilterDto;
use PHPUnit\Framework\TestCase;

class ProductFilterDtoTest extends TestCase
{
    public function testGettersReturnCorrectValues(): void
    {
        // Arrange: DTO with all filters
        $dto = new ProductFilterDto('Apple', 100, 500);

        // Act & Assert: Verify getter values
        $this->assertEquals('Apple', $dto->getName());
        $this->assertEquals(100, $dto->getMinWeight());
        $this->assertEquals(500, $dto->getMaxWeight());
    }

    public function testGettersWithNullValues(): void
    {
        // Arrange: DTO with null values
        $dto = new ProductFilterDto();

        // Act & Assert: Verify getters return null
        $this->assertNull($dto->getName());
        $this->assertNull($dto->getMinWeight());
        $this->assertNull($dto->getMaxWeight());
    }

    public function testHasFiltersReturnsTrueWhenNameIsSet(): void
    {
        // Arrange: DTO with only name filter
        $dto = new ProductFilterDto('Apple');

        // Act & Assert: hasFilters() should return true
        $this->assertTrue($dto->hasFilters());
    }

    public function testHasFiltersReturnsTrueWhenMinWeightIsSet(): void
    {
        // Arrange: DTO with only minWeight filter
        $dto = new ProductFilterDto(null, 100);

        // Act & Assert: hasFilters() should return true
        $this->assertTrue($dto->hasFilters());
    }

    public function testHasFiltersReturnsTrueWhenMaxWeightIsSet(): void
    {
        // Arrange: DTO with only maxWeight filter
        $dto = new ProductFilterDto(null, null, 500);

        // Act & Assert: hasFilters() should return true
        $this->assertTrue($dto->hasFilters());
    }

    public function testHasFiltersReturnsTrueWhenAllFiltersAreSet(): void
    {
        // Arrange: DTO with all filters set
        $dto = new ProductFilterDto('Apple', 100, 500);

        // Act & Assert: hasFilters() should return true
        $this->assertTrue($dto->hasFilters());
    }

    public function testHasFiltersReturnsFalseWhenNoFiltersAreSet(): void
    {
        // Arrange: DTO with no filters set
        $dto = new ProductFilterDto();

        // Act & Assert: hasFilters() should return false
        $this->assertFalse($dto->hasFilters());
    }
}
