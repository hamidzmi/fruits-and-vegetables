<?php

namespace App\Controller;

use App\Domain\Dto\ProcessRequestDto;
use App\Domain\UseCase\ProcessRequestUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProcessController extends AbstractController
{
    private ProcessRequestUseCase $processRequestUseCase;

    public function __construct(ProcessRequestUseCase $processRequestUseCase)
    {
        $this->processRequestUseCase = $processRequestUseCase;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $dto = new ProcessRequestDto($data);
            $dto->validate();

            $result = $this->processRequestUseCase->execute($dto);

            return new JsonResponse($result, JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
