<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;

class FruitCollection extends ProductCollection
{
    public function add(Product $item): void
    {
        if (!$item->getType()->equals(ProductType::fruit)) {
            throw new \InvalidArgumentException("Only fruits can be added to FruitCollection.");
        }

        $this->items[$item->getId()] = $item;
    }
}