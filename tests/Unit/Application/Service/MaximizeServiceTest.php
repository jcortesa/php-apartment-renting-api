<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\Command\MaximizeCommand;
use App\Application\Query\MaximizeQuery;
use App\Application\Service\MaximizeService;
use App\Domain\Service\BookingsCombinator;
use App\Domain\Service\ProfitCalculator;
use App\Infrastructure\Controller\Model\BookingRequestDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MaximizeService::class)]
final class MaximizeServiceTest extends TestCase
{
    public function testMaximizeBookings(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingsCombinator = new BookingsCombinator();
        $maximizeService = new MaximizeService($profitCalculator, $bookingsCombinator);
        $bookingRequests = [
            new BookingRequestDto('request-1','2023-10-01', 4, 1000, 20),
            new BookingRequestDto('request-2','2023-10-03', 4, 800, 15),
            new BookingRequestDto('request-3','2023-10-06', 3, 600, 10),
        ];

        $expectedResult = new MaximizeQuery(['request-1', 'request-3'], 260, 20,35,50);
        $maximizeCommand = new MaximizeCommand($bookingRequests);

        $maximizeQuery = $maximizeService->run($maximizeCommand);

        self::assertEqualsCanonicalizing($expectedResult, $maximizeQuery);
    }

}
