<?php

namespace App\Tests\Unit\Domain\Dto;

use App\Domain\Dto\AddItemToCollectionDto;
use PHPUnit\Framework\TestCase;

class AddItemToCollectionDtoTest extends TestCase
{
    public function testValidDto(): void
    {
        $data = [
            'id' => '12345',
            'name' => 'Sample Item',
            'quantity' => 10,
            'unit' => 'kg'
        ];

        $dto = new AddItemToCollectionDto($data);
        $dto->validate();

        $this->assertSame('12345', $dto->getId());
        $this->assertSame('Sample Item', $dto->getName());
        $this->assertSame(10, $dto->getQuantity());
        $this->assertSame('kg', $dto->getUnit());
    }

    public function testInvalidDtoMissingFields(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [];
        $dto = new AddItemToCollectionDto($data);
        $dto->validate();
    }

    public function testInvalidQuantity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Quantity must be a positive number.");

        $data = [
            'id' => '12345',
            'name' => 'Sample Item',
            'quantity' => -5,
            'unit' => 'kg'
        ];

        $dto = new AddItemToCollectionDto($data);
        $dto->validate();
    }

    public function testInvalidUnit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit must be 'g' or 'kg'.");

        $data = [
            'id' => '12345',
            'name' => 'Sample Item',
            'quantity' => 10,
            'unit' => 'lb'
        ];

        $dto = new AddItemToCollectionDto($data);
        $dto->validate();
    }

    public function testEmptyStringsForFields(): void
    {
        $this->expectException(\TypeError::class);

        $data = [
            'id' => '',
            'name' => '',
            'quantity' => '',
            'unit' => ''
        ];

        $dto = new AddItemToCollectionDto($data);
        $dto->validate();
    }

    public function testBoundaryCaseForUnit(): void
    {
        $data = [
            'id' => '12345',
            'name' => 'Sample Item',
            'quantity' => 1,
            'unit' => 'g'
        ];

        $dto = new AddItemToCollectionDto($data);
        $dto->validate();

        $this->assertSame('g', $dto->getUnit());
    }
}
