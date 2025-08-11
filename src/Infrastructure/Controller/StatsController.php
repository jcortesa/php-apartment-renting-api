<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\StatsCommand;
use App\Application\Service\StatsService;
use App\Infrastructure\Controller\Model\BookingRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class StatsController
{
    public function __construct(private StatsService $statsService)
    {
    }

    /**
     * @param list<BookingRequestDto> $bookingRequestList
     */
    #[Route('/stats', methods: ['POST'], format: 'json')]
    public function __invoke(
        #[MapRequestPayload(type: BookingRequestDto::class)]
        array $bookingRequestList
    ): JsonResponse
    {
        $statsCommand = new StatsCommand($bookingRequestList);
        $statsQuery = $this->statsService->run($statsCommand);

        return new JsonResponse([
            'avg_night' => $statsQuery->average,
            'min_night' => $statsQuery->minimum,
            'max_night' => $statsQuery->maximum,
        ]);
    }
}
