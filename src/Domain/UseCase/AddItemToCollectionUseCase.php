<?php

namespace App\Domain\UseCase;

use App\Domain\Dto\AddItemToCollectionDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;

class AddItemToCollectionUseCase
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    public function execute(string $type, AddItemToCollectionDto $dto): void
    {
        $product = new Product(
            $dto->getId(),
            $dto->getName(),
            ProductType::from($type),
            Weight::from($dto->getQuantity(), $dto->getUnit())
        );

        $this->collectionService->addItemToCollection(ProductType::from($type), $product);
    }
}
