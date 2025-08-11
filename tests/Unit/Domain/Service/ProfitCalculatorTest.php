<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\BookingRequest;
use App\Domain\Service\ProfitCalculator;
use PHPUnit\Framework\TestCase;

final class ProfitCalculatorTest extends TestCase
{
    public function testWhenCalculateProfitPerNightThenReturnsExpectedProfit(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingRequest = new BookingRequest('request-id', '2023-10-01', 5, 1000, 20);

        $profitPerNight = $profitCalculator->calculateProfitPerNight($bookingRequest);

        $this->assertEquals(40, $profitPerNight);
    }

    public function testWhenCalculateTotalProfitThenReturnsExpectedTotalProfit(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingRequestList = [
            new BookingRequest('request-id-1', '2023-10-01', 5, 1000, 20),
            new BookingRequest('request-id-2', '2023-10-20', 3, 1500, 15),
        ];

        $totalProfit = $profitCalculator->calculateTotalProfit($bookingRequestList);

        $this->assertEquals(425, $totalProfit);
    }

    public function testWhenCalculateProfitMetricsThenReturnsExpectedMetrics(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingRequestList = [
            new BookingRequest('request-id-1', '2023-10-01', 5, 1000, 20),
            new BookingRequest('request-id-2', '2023-10-20', 3, 1500, 15),
            new BookingRequest('request-id-3', '2023-10-25', 2, 800, 10),
        ];

        $metrics = $profitCalculator->calculateProfitMetrics($bookingRequestList);

        $this->assertEquals(51.67, $metrics[0]);
        $this->assertEquals(40.00, $metrics[1]);
        $this->assertEquals(75.00, $metrics[2]);
    }
}
