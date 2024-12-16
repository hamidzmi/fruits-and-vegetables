<?php

namespace App\Domain\Repository;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\ProductType;

interface ProductRepositoryInterface
{
    public function saveCollection(ProductCollection $collection, ProductType $type): void;

    /**
     * @param ProductType $type
     * @return Product[]
     */
    public function findByType(ProductType $type): array;
}
