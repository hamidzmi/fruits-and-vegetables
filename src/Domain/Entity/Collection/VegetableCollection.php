<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;

class VegetableCollection extends ProductCollection
{
    public function add(Product $item): void
    {
        if (!$item->getType()->equals(ProductType::vegetable)) {
            throw new \InvalidArgumentException("Only vegetables can be added to VegetableCollection.");
        }

        $this->items[$item->getId()] = $item;
    }
}