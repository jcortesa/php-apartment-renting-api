<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\Command\MaximizeCommand;
use App\Application\Query\MaximizeQuery;
use App\Application\Service\MaximizeService;
use App\Domain\Service\BookingsCombinator;
use App\Domain\Service\ProfitCalculator;
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
            ['request_id' => 'request-1', 'check_in' => '2023-10-01', 'nights' => 4, 'selling_rate' => 1000, 'margin' => 20],
            ['request_id' => 'request-2', 'check_in' => '2023-10-03', 'nights' => 4, 'selling_rate' => 800, 'margin' => 15],
            ['request_id' => 'request-3', 'check_in' => '2023-10-06', 'nights' => 3, 'selling_rate' => 600, 'margin' => 10],
        ];

        $expectedResult = new MaximizeQuery(['request-1', 'request-3'], 260, 20,35,50);
        $maximizeCommand = new MaximizeCommand($bookingRequests);

        $maximizeQuery = $maximizeService->run($maximizeCommand);

        self::assertEqualsCanonicalizing($expectedResult, $maximizeQuery);
    }

}
