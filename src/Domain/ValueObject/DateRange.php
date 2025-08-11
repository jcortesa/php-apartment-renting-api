<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final readonly class DateRange
{
    public \DateTimeImmutable $checkIn;
    public \DateTimeImmutable $checkOut;
    public function __construct(
        string $checkIn,
        public int $nights
    ) {
        $this->checkIn = \DateTimeImmutable::createFromFormat('Y-m-d', $checkIn);
        $this->checkOut = $this->checkIn->add(new \DateInterval("P{$this->nights}D"));
    }

    public function overlaps(self $other): bool
    {
        return $this->checkIn < $other->checkOut && $this->checkOut > $other->checkIn;
    }
}
