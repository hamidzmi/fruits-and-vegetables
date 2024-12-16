<?php

namespace App\Domain\Repository;

use App\Domain\Dto\ProductFilter;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;

interface ProductRepositoryInterface
{
    public function saveCollection(ProductCollection $collection): void;
    public function findByExternalId(int $value): ?Product;

    public function findByTypeAndFilter(ProductType $type, ProductFilter $filter): array;
}
