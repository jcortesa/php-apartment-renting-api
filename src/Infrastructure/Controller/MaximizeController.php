<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\MaximizeCommand;
use App\Application\Service\MaximizeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final readonly class MaximizeController
{
    public function __construct(private MaximizeService $maximizeService)
    {
    }

    #[Route('/maximize', methods: ['POST'])]
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

        $maximizeCommand = new MaximizeCommand($data);
        $maximizeQuery = $this->maximizeService->run($maximizeCommand);

        return new JsonResponse([
            'request_ids' => $maximizeQuery->requestIds,
            'total_profit' => $maximizeQuery->totalProfit,
            'avg_night' => $maximizeQuery->average,
            'min_night' => $maximizeQuery->minimum,
            'max_night' => $maximizeQuery->maximum,
        ]);
    }
}
