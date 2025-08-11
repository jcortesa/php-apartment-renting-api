<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\BookingRequest;

final readonly class ProfitCalculator
{
    /**
     * @param list<BookingRequest> $bookingRequestList
     */
    public function calculateTotalProfit(array $bookingRequestList): float
    {
        return array_reduce($bookingRequestList, static function (float $carry, BookingRequest $bookingRequest) {
            return $carry + ($bookingRequest->sellingRate->amount * ($bookingRequest->margin / 100));
        }, 0.0);
    }

    /**
     * @param list<BookingRequest> $bookingRequestList
     *
     * @return array{float, float, float}
     */
    public function calculateProfitMetrics(array $bookingRequestList): array
    {
        /** @var non-empty-list<float> $profitsPerNightList */
        $profitsPerNightList = array_map(
            fn(BookingRequest $bookingRequest) => $bookingRequest->calculateProfitPerNight()->amount,
            $bookingRequestList
        );

        return [
            round(array_sum($profitsPerNightList) / count($profitsPerNightList), 2),
            min($profitsPerNightList),
            max($profitsPerNightList),
        ];
    }
}
