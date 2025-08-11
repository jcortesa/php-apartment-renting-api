<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;

final readonly class BookingRequest
{
    public function __construct(
        public string $requestId,
        public DateRange $dateRange,
        public Money $sellingRate,
        public float $margin,
    )
    {
    }

    public function calculateProfit(): Money
    {
        $profit = $this->sellingRate->amount * ($this->margin / 100);

        return new Money($profit);
    }

    public function calculateProfitPerNight(): Money
    {
        $profitPerNight = $this->calculateProfit()->amount / $this->dateRange->nights;

        return new Money($profitPerNight);
    }
}
