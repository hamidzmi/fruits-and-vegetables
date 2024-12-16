<?php

namespace App\Controller;

use App\Domain\Service\ProductProcessorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductProcessorController extends AbstractController
{
    public function __construct(
        protected ProductProcessorService $productProcessorService,
    ){}

    #[Route('/api/process', name: 'process', methods: ['POST'])]
    public function processFile(Request $request): JsonResponse
    {
        if (!empty($request->getContent())) {
            $data = json_decode($request->getContent(), true);
        } else {
            $projectRoot = $this->getParameter('kernel.project_dir');
            $data = json_decode(file_get_contents($projectRoot . '/request.json'), true);
        }

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON format'], 400);
        }

        $collections = $this->productProcessorService->process($data);
        foreach ($collections as $collection) {
            $this->productProcessorService->storeCollection($collection);
        }

        return new JsonResponse([
            'fruits' => array_map(fn($item) => $item->toArray(), $collections['fruits']->list()),
            'vegetables' => array_map(fn($item) => $item->toArray(), $collections['vegetables']->list()),
        ]);
    }
}
