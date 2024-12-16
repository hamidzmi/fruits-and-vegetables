<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Product;

abstract class ProductCollection
{
    /** @var Product[] */
    protected array $items = [];

    abstract public function add(Product $item): void;

    public function remove(int $id): void
    {
        unset($this->items[$id]);
    }

    public function list(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_values(array_map(fn(Product $product) => $product->toArray(), $this->items));
    }

    public function search(ProductFilterDto $filterDto): array
    {
        return array_values(array_filter($this->items, function (Product $product) use ($filterDto) {
            $matchesName = $filterDto->getName() === null || stripos($product->getName(), $filterDto->getName()) !== false;
            $matchesMinWeight = empty($filterDto->getMinWeight()) || $product->getWeight()->toGrams() >= $filterDto->getMinWeight();
            $matchesMaxWeight = empty($filterDto->getMaxWeight()) || $product->getWeight()->toGrams() <= $filterDto->getMaxWeight();

            return $matchesName && $matchesMinWeight && $matchesMaxWeight;
        }));
    }
}
