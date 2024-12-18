<?php

namespace App\Domain\Service;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\ValueObject\ProductType;
use InvalidArgumentException;

class ProductCollectionFactory
{
    public function make(ProductType $type): ProductCollection
    {
        return match ($type) {
            ProductType::fruit => new FruitCollection(),
            ProductType::vegetable => new VegetableCollection(),
            default => throw new InvalidArgumentException("Unknown products type: $type->value"),
        };
    }
}
