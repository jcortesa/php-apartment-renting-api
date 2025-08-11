<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\BookingRequest;
use App\Domain\Service\BookingsCombinator;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BookingsCombinator::class)]
final class BookingsCombinatorTest extends TestCase
{
    public function testGetValidCombinations(): void
    {
        $bookingsCombinator = new BookingsCombinator();

        $bookingRequests = [
            new BookingRequest('request-1', new DateRange('2023-10-01', 4), new Money(1000.00), 20.0),
            new BookingRequest('request-2', new DateRange('2023-10-03', 4), new Money(800.00), 15.0),
            new BookingRequest('request-3', new DateRange('2023-10-06', 3), new Money(600.00), 10.0),
        ];
        $expectedResult = [
            [
                new BookingRequest('request-1', new DateRange('2023-10-01', 4), new Money(1000.00), 20.0),
            ],
            [
                new BookingRequest('request-2', new DateRange('2023-10-03', 4), new Money(800.00), 15.0),
            ],
            [
                new BookingRequest('request-3', new DateRange('2023-10-06', 3), new Money(600.00), 10.0),
            ],
            [
                new BookingRequest('request-1', new DateRange('2023-10-01', 4), new Money(1000.00), 20.0),
                new BookingRequest('request-3', new DateRange('2023-10-06', 3), new Money(600.00), 10.0),
            ],
        ];

        $validCombinations = $bookingsCombinator->getValidCombinations($bookingRequests);

        self::assertEqualsCanonicalizing($expectedResult, $validCombinations);
    }
}
