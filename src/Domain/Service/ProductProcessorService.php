<?php

namespace App\Domain\Service;

use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;

class ProductProcessorService
{
    public function __construct(
        protected ProductCollectionManager   $productCollectionManager,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function process(array $data): array
    {
        foreach ($data as $entry) {
            $product = new Product(
                $entry['id'],
                $entry['name'],
                ProductType::from($entry['type']),
                Weight::from($entry['quantity'], $entry['unit']),
            );

            $this->productCollectionManager->addItem($product);
        }

        return $this->productCollectionManager->getCollections();
    }

    public function storeCollection(ProductCollection $collection): void
    {
        $this->productRepository->saveCollection($collection);
    }
}
