<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Model\StatsRequest;
use App\Application\Service\StatsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final readonly class StatsController
{
    public function __construct(private StatsService $statsService)
    {
    }

    #[Route('/stats', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        /**
         * @var list<array{
         *     request_id: string,
         *     check_in: string,
         *     nights: int,
         *     selling_rate: int,
         *     margin: int
         * }> $data
         */
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $statsRequest = new StatsRequest($data);
        $statsResponse = $this->statsService->run($statsRequest);

        return new JsonResponse([
            'avg_night' => $statsResponse->average,
            'min_night' => $statsResponse->minimum,
            'max_night' => $statsResponse->maximum,
        ]);
    }
}
