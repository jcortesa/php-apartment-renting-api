<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\StatsCommand;
use App\Application\Query\StatsQuery;
use App\Domain\Entity\BookingRequest;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;
use App\Infrastructure\Controller\Model\BookingRequestDto;

final class StatsService
{
    public function run(StatsCommand $statsCommand): StatsQuery
    {
        $bookingRequestList = array_map(static function (BookingRequestDto $bookingRequestDto) {
            return new BookingRequest(
                $bookingRequestDto->requestId,
                new DateRange($bookingRequestDto->checkIn, $bookingRequestDto->nights),
                new Money($bookingRequestDto->sellingRate),
                $bookingRequestDto->margin
            );
        }, $statsCommand->bookings);

        /** @var non-empty-list<float> $profitPerNightList */
        $profitPerNightList = array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->calculateProfitPerNight()->amount,
            $bookingRequestList
        );

        [$average, $minimum, $maximum] = self::getStats($profitPerNightList);

        return new StatsQuery($average, $minimum, $maximum);
    }

    /**
     * @param non-empty-list<float> $profitPerNightList
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
