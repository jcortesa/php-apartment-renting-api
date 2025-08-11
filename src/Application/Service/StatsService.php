<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\StatsCommand;
use App\Application\Query\StatsQuery;
use App\Domain\Entity\BookingRequest;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;

final class StatsService
{
    public function run(StatsCommand $statsRequest): StatsQuery
    {
        /** @var list<BookingRequest> $bookingRequestList */
        $bookingRequestList = array_map(static fn($data) => new BookingRequest(
            $data['request_id'],
            new DateRange($data['check_in'], $data['nights']),
            new Money($data['selling_rate']),
            $data['margin']
        ), $statsRequest->data);

        /** @var list<float> $profitPerNightList */
        $profitPerNightList = array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->calculateProfitPerNight()->amount,
            $bookingRequestList
        );

        [$average, $minimum, $maximum] = self::getStats($profitPerNightList);

        return new StatsQuery($average, $minimum, $maximum);
    }

    /**
     * @param list<float> $profitPerNightList
     *
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
