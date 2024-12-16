<?php

namespace App\Controller;

use App\Domain\Dto\AddItemToCollectionDto;
use App\Domain\UseCase\AddItemToCollectionUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddItemToCollectionController extends AbstractController
{
    private AddItemToCollectionUseCase $addItemToCollectionUseCase;

    public function __construct(AddItemToCollectionUseCase $addItemToCollectionUseCase)
    {
        $this->addItemToCollectionUseCase = $addItemToCollectionUseCase;
    }

    public function __invoke(Request $request, string $type): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $dto = new AddItemToCollectionDto($data);
            $dto->validate();

            $this->addItemToCollectionUseCase->execute($type, $dto);

            return new JsonResponse(['success' => true], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
