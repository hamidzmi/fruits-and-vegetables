<?php

namespace App\Tests\Unit\Domain\Dto;

use App\Domain\Dto\ProcessRequestDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProcessRequestDtoTest extends TestCase
{
    public function testValidateWithValidData(): void
    {
        // Arrange: Valid product data
        $data = [
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => 500,
                'unit' => 'g'
            ],
            [
                'id' => '2',
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 300,
                'unit' => 'kg'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act & Assert: No exception should be thrown for valid data
        $this->assertNull($dto->validate());
    }

    public function testValidateThrowsExceptionForMissingFields(): void
    {
        // Arrange: Missing 'name' field
        $data = [
            [
                'id' => '1',
                'type' => 'fruit',
                'quantity' => 500,
                'unit' => 'g'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act & Assert: Expect an InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);

        $dto->validate();
    }

    public function testValidateThrowsExceptionForInvalidType(): void
    {
        // Arrange: Invalid 'type' field
        $data = [
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'invalid_type',
                'quantity' => 500,
                'unit' => 'g'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act & Assert: Expect an InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Type must be 'fruit' or 'vegetable'.");

        $dto->validate();
    }

    public function testValidateThrowsExceptionForNegativeQuantity(): void
    {
        // Arrange: Negative 'quantity' field
        $data = [
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => -500,
                'unit' => 'g'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act & Assert: Expect an InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Quantity must be a positive number.");

        $dto->validate();
    }

    public function testValidateThrowsExceptionForInvalidUnit(): void
    {
        // Arrange: Invalid 'unit' field
        $data = [
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => 500,
                'unit' => 'invalid_unit'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act & Assert: Expect an InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit must be 'g' or 'kg'.");

        $dto->validate();
    }

    public function testGetProductsReturnsProductsArray(): void
    {
        // Arrange: Valid product data
        $data = [
            [
                'id' => '1',
                'name' => 'Apple',
                'type' => 'fruit',
                'quantity' => 500,
                'unit' => 'g'
            ]
        ];

        $dto = new ProcessRequestDto($data);

        // Act: Retrieve the products
        $products = $dto->getProducts();

        // Assert: Verify the products array matches the input
        $this->assertCount(1, $products);
        $this->assertEquals('1', $products[0]['id']);
        $this->assertEquals('Apple', $products[0]['name']);
        $this->assertEquals('fruit', $products[0]['type']);
        $this->assertEquals(500, $products[0]['quantity']);
        $this->assertEquals('g', $products[0]['unit']);
    }
}
