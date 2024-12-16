<?php

namespace App\Domain\Service;

use App\Domain\Dto\ProductFilter;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ProductType;
use Psr\Log\LoggerInterface;

class ProductQueryService
{
    public function __construct(
        protected ProductCollectionManager   $collectionManager,
        protected ProductRepositoryInterface $productRepository,
    ) {}

    public function loadFilteredCollections(ProductFilter $filter, ?ProductType $type = null): array
    {
        if (!$type) {
            $this->processProductsByType(ProductType::fruit, $filter);
            $this->processProductsByType(ProductType::vegetable, $filter);
        } else {
            $this->processProductsByType($type, $filter);
        }

        return $this->collectionManager->getCollections();
    }

    private function processProductsByType(ProductType $type, ProductFilter $filter): void
    {
        $products = $this->productRepository->findByTypeAndFilter($type, $filter);

        foreach ($products as $product) {
            $this->collectionManager->addItem($product);
        }
    }
}
