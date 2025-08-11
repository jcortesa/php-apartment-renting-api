<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\BookingRequest;

final readonly class ProfitCalculator
{
    public function calculateProfitPerNight(BookingRequest $bookingRequest): float
    {
        // @TODO check for possible errors
        return ($bookingRequest->sellingRate * ($bookingRequest->margin / 100)) / $bookingRequest->nights;
    }

    /**
     * @param list<BookingRequest> $bookingRequestList
     */
    public function calculateTotalProfit(array $bookingRequestList): float
    {
        return array_reduce($bookingRequestList, static function (float $carry, BookingRequest $bookingRequest) {
            return $carry + ($bookingRequest->sellingRate * ($bookingRequest->margin / 100));
        }, 0.0);
    }

    /**
     * @param list<BookingRequest> $bookingRequestList
     *
     * @return array{float, float, float}
     */
    public function calculateProfitMetrics(array $bookingRequestList): array
    {
        $profitsPerNightList = array_map(
            fn(BookingRequest $bookingRequest) => $this->calculateProfitPerNight($bookingRequest),
            $bookingRequestList
        );

        return [
            round(array_sum($profitsPerNightList) / count($profitsPerNightList), 2),
            min($profitsPerNightList),
            max($profitsPerNightList),
        ];
    }
}
