<?php

declare(strict_types=1);

namespace App\Domain\Entity;

final readonly class BookingRequest
{
    public function __construct(
        public string $requestId,
        public string $checkIn,
        public int $nights,
        public int $sellingRate,
        public int $margin,
    )
    {
    }
}
