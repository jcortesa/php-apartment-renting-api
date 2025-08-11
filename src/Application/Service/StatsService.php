<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Model\StatsRequest;
use App\Application\Model\StatsResponse;
use App\Domain\Entity\BookingRequest;

final class StatsService
{
    public function run(StatsRequest $statsRequest): StatsResponse
    {
        /** @var list<BookingRequest> $bookingRequestList */
        $bookingRequestList = array_map(static fn($data) => new BookingRequest(
            $data['request_id'],
            $data['check_in'],
            $data['nights'],
            $data['selling_rate'],
            $data['margin']
        ), $statsRequest->data);

        /** @var list<float> $profitPerNightList */
        $profitPerNightList = array_map(
            static fn(BookingRequest $bookingRequest) => self::calculateProfitPerNight($bookingRequest->sellingRate, $bookingRequest->margin, $bookingRequest->nights),
            $bookingRequestList
        );

        [$average, $minimum, $maximum] = self::getStats($profitPerNightList);

        return new StatsResponse($average, $minimum, $maximum);
    }

    private static function calculateProfitPerNight(int $sellingRate, int $margin, int $nights): float
    {
        return ($sellingRate * ($margin / 100)) / $nights;
    }

    /**
     * @param list<float> $profitPerNightList
     * @return list<float>
     */
    private static function getStats(array $profitPerNightList): array
    {
        return [
            round(array_sum($profitPerNightList) / count($profitPerNightList), 2),
            min($profitPerNightList),
            max($profitPerNightList),
        ];
    }
}
