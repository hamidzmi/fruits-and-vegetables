<?php

namespace App\Controller;

use App\Domain\Dto\ProductFilter;
use App\Domain\Entity\Collection\ProductCollection;
use App\Domain\Entity\Product;
use App\Domain\Service\ProductQueryService;
use App\Domain\ValueObject\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductCollectionController extends AbstractController
{
    public function __construct(protected ProductQueryService $productQueryService) {}

    #[Route('/api/collections', name: 'api_collections', methods: ['GET'])]
    public function getCollections(Request $request): JsonResponse
    {
        $filter = new ProductFilter(
            $request->query->get('name'),
            $request->query->getInt('minWeight'),
            $request->query->getInt('maxWeight')
        );

        $collections = $this->productQueryService->loadFilteredCollections($filter);

        return $this->json([
            'fruits' => $this->serializeCollection($collections['fruits']),
            'vegetables' => $this->serializeCollection($collections['vegetables']),
        ]);
    }

    #[Route('/api/collections/{type}', name: 'api_collection_fruit', methods: ['GET'])]
    public function getFruitCollection(Request $request, string $type): JsonResponse
    {
        $productType = ProductType::tryFrom($type);
        if (!$productType) {
            return $this->json(['error' => 'Invalid product type'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $filter = new ProductFilter(
            $request->query->get('name'),
            $request->query->getInt('minWeight'),
            $request->query->getInt('maxWeight')
        );

        $collections = $this->productQueryService->loadFilteredCollections($filter, $productType);

        $serializedCollection = match ($productType) {
            ProductType::fruit => $this->serializeCollection($collections['fruits']),
            ProductType::vegetable => $this->serializeCollection($collections['vegetables']),
        };

        return $this->json($serializedCollection);
    }

    private function serializeCollection(ProductCollection $collection): array
    {
        $result = array_map(function (Product $item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'type' => $item->getType()->value,
                'weight' => $item->getWeight()->toGrams(),
            ];
        }, $collection->list());

        return array_values($result);
    }
}
