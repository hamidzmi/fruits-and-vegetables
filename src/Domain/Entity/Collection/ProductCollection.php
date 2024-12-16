<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\Product;

abstract class ProductCollection
{
    /** @var Product[] */
    protected array $items = [];

    abstract public function add(Product $item): void;

    public function remove(Product $item): void
    {
        unset($this->items[$item->getId()]);
    }

    public function list(): array
    {
        return $this->items;
    }
}