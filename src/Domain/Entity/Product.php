<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;

class Product
{
    private int $id;
    private string $name;
    private ProductType $type;
    private Weight $weight;

    public function __construct(int $id, string $name, ProductType $type, Weight $weight)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->weight = $weight;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'weight' => $this->weight->toGrams()
        ];
    }
}
