<?php

namespace App\Domain\Service;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Collection\FruitCollection;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Collection\VegetableCollection;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ProductType;
use InvalidArgumentException;

class CollectionService
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve a collection by type.
     *
     * @param ProductType $type
     * @return ProductCollection
     */
    public function getCollection(ProductType $type): ProductCollection
    {
        $products = $this->repository->findByType($type);

        if ($type->equals(ProductType::fruit)) {
            $collection = new FruitCollection();
        } elseif ($type->equals(ProductType::vegetable)) {
            $collection = new VegetableCollection();
        } else {
            throw new InvalidArgumentException("Unknown products type: $type->value");
        }

        if (!$products) {
            return $collection;
        }

        foreach ($products as $product) {
            $collection->add($product);
        }

        return $collection;
    }

    /**
     * Add an item to a collection.
     *
     * @param ProductType $type
     * @param Product $product
     * @return void
     */
    public function addItemToCollection(ProductType $type, Product $product): void
    {
        $collection = $this->getCollection($type);
        $collection->add($product);
        $this->repository->saveCollection($collection, $type);
    }

    /**
     * List items from a collection.
     *
     * @param ProductType $type
     * @return Product[]
     */
    public function listItemsFromCollection(ProductType $type): array
    {
        $collection = $this->getCollection($type);
        return array_values($collection->list());
    }

    /**
     * Search items in a collection based on criteria.
     *
     * @param ProductType $type
     * @param ProductFilterDto $filterDto
     * @return array
     */
    public function searchItemsInCollection(ProductType $type, ProductFilterDto $filterDto): array
    {
        $collection = $this->getCollection($type);
        return $collection->search($filterDto);
    }
}
