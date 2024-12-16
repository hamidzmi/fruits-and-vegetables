<?php

namespace App\Domain\UseCase;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\ValueObject\ProductType;

class QueryCollectionsUseCase
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Execute the query with optional filters.
     *
     * @param ProductType $type
     * @param ProductFilterDto $filterDto
     * @return array
     */
    public function execute(ProductType $type, ProductFilterDto $filterDto): array
    {
        // Retrieve the collection from the service
        if ($filterDto->hasFilters()) {
            // Use search functionality if filters are provided
            return array_values(
                array_map(fn(Product $p) => $p->toArray(),
                    $this->collectionService->searchItemsInCollection($type, $filterDto)
                )
            );
        }

        // Return the full list if no filters are provided
        return array_values(
            array_map(fn(Product $p) => $p->toArray(), $this->collectionService->listItemsFromCollection($type))
        );
    }
}
