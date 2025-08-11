<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Controller;

use App\Application\Service\StatsService;
use App\Infrastructure\Controller\Model\BookingRequestDto;
use App\Infrastructure\Controller\StatsController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatsController::class)]
final class StatsControllerTest extends TestCase
{
    public function testWhenInvokeThenReturnProperResponse(): void
    {
        $bookingRequestList = [
            new BookingRequestDto(
                'req-1',
                '2023-10-01',
                5,
                1000,
                20,
            ),
            new BookingRequestDto(
                'req-2',
                '2023-10-05',
                3,
                1500,
                15,
            ),
            new BookingRequestDto(
                'req-3',
                '2023-10-10',
                2,
                800,
                10,
            ),
        ];
        $statsService = new StatsService();
        $controller = new StatsController($statsService);
        $expectedResponse = json_encode([
            'avg_night' => 51.67,
            'min_night' => 40,
            'max_night' => 75
        ], JSON_THROW_ON_ERROR);

        $response = $controller->__invoke($bookingRequestList);

        self::assertSame($expectedResponse, $response->getContent());
    }
}
