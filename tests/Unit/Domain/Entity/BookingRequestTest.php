<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\BookingRequest;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BookingRequest::class)]
final class BookingRequestTest extends TestCase
{
    public function testCalculateProfit(): void
    {
        $bookingRequest = new BookingRequest(
            'request-123',
            new DateRange('2023-10-01', 5),
            new Money(1000.00),
            20.0
        );

        $profit = $bookingRequest->calculateProfit();

        $this->assertEquals(200.00, $profit->amount);
    }

    public function testCalculateProfitPerNight(): void
    {
        $bookingRequest = new BookingRequest(
            'request-123',
            new DateRange('2023-10-01', 5),
            new Money(1000.00),
            20.0
        );

        $profitPerNight = $bookingRequest->calculateProfitPerNight();

        $this->assertEquals(40.00, $profitPerNight->amount);
    }
}
