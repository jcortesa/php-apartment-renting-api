<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\MaximizeCommand;
use App\Application\Service\MaximizeService;
use App\Infrastructure\Controller\Model\BookingRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class MaximizeController
{
    public function __construct(private MaximizeService $maximizeService)
    {
    }

    /**
     * @param list<BookingRequestDto> $bookingRequestList
     */
    #[Route('/maximize', methods: ['POST'], format: 'json')]
    public function __invoke(
        #[MapRequestPayload(type: BookingRequestDto::class)]
        array $bookingRequestList
    ): JsonResponse
    {
        $maximizeCommand = new MaximizeCommand($bookingRequestList);
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
