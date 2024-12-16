<?php

namespace App\Domain\UseCase;

use App\Domain\Dto\ProcessRequestDto;
use App\Domain\Entity\Product;
use App\Domain\Service\CollectionService;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Weight;

class ProcessRequestUseCase
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    public function execute(ProcessRequestDto $dto): array
    {
        foreach ($dto->getProducts() as $item) {
            $product = new Product(
                $item['id'],
                $item['name'],
                ProductType::from($item['type']),
                Weight::from($item['quantity'], $item['unit'])
            );

            $type = ProductType::from($item['type']);
            $this->collectionService->addItemToCollection($type, $product);
        }

        return [
            'fruits' => $this->collectionService->getCollection(ProductType::fruit)->toArray(),
            'vegetables' => $this->collectionService->getCollection(ProductType::vegetable)->toArray(),
        ];
    }
}
