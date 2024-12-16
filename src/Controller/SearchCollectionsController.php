<?php

namespace App\Controller;

use App\Domain\Dto\ProductFilterDto;
use App\Domain\UseCase\QueryCollectionsUseCase;
use App\Domain\ValueObject\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchCollectionsController extends AbstractController
{
    private QueryCollectionsUseCase $queryCollectionsUseCase;

    public function __construct(QueryCollectionsUseCase $queryCollectionsUseCase)
    {
        $this->queryCollectionsUseCase = $queryCollectionsUseCase;
    }

    public function __invoke(Request $request, string $type): JsonResponse
    {
        try {
            $productType = ProductType::from($type);

            // Create a ProductFilterDto from query parameters
            $filterDto = new ProductFilterDto(
                $request->query->get('name'),
                $request->query->getInt('minWeight'),
                $request->query->getInt('maxWeight')
            );

            $result = $this->queryCollectionsUseCase->execute($productType, $filterDto);

            return new JsonResponse($result, JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
