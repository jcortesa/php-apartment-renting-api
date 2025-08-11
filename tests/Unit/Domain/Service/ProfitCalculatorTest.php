<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\BookingRequest;
use App\Domain\Service\ProfitCalculator;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProfitCalculator::class)]
final class ProfitCalculatorTest extends TestCase
{
    public function testWhenCalculateTotalProfitThenReturnsExpectedTotalProfit(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingRequestList = [
            new BookingRequest('request-id-1', new DateRange('2023-10-01', 5), new Money(1000), 20),
            new BookingRequest('request-id-2', new DateRange('2023-10-20', 3), new Money(1500), 15),
        ];

        $totalProfit = $profitCalculator->calculateTotalProfit($bookingRequestList);

        $this->assertEquals(425, $totalProfit);
    }

    public function testWhenCalculateProfitMetricsThenReturnsExpectedMetrics(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingRequestList = [
            new BookingRequest('request-id-1', new DateRange('2023-10-01', 5), new Money(1000), 20),
            new BookingRequest('request-id-2', new DateRange('2023-10-20', 3), new Money(1500), 15),
            new BookingRequest('request-id-3', new DateRange('2023-10-25', 2), new Money(800), 10),
        ];

        $metrics = $profitCalculator->calculateProfitMetrics($bookingRequestList);

        $this->assertEquals(51.67, $metrics[0]);
        $this->assertEquals(40.00, $metrics[1]);
        $this->assertEquals(75.00, $metrics[2]);
    }
}
