<?php

declare(strict_types=1);

namespace App\Application\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class StatsController
{
    #[Route('/stats', methods: ['POST'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
