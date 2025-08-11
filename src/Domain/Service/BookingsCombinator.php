<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\BookingRequest;

final readonly class BookingsCombinator
{
    /**
     * @param list<BookingRequest> $bookingRequestList
     * @return list<list<BookingRequest>>
     */
    public function getValidCombinations(array $bookingRequestList): array
    {
        $filtered = array_filter(
            $this->generateCombinations($bookingRequestList),
            /** @param list<BookingRequest> $combination */
            function (array $combination): bool {
                return $this->visitCombination($combination) !== null;
            }
        );

        return array_values($filtered);
    }

    /**
     * @param list<BookingRequest> $array
     * @return list<list<BookingRequest>>
     */
    private function generateCombinations(array $array): array
    {
        $powerSet = [[]];

        for ($i = count($array) - 1; $i >= 0; $i--) {
            foreach ($powerSet as $subset) {
                $powerSet[] = array_merge([$array[$i]], $subset);
            }
        }

        return array_values(array_filter($powerSet));
    }

    /**
     * @param list<BookingRequest> $combination
     * @return list<BookingRequest>|null
     */
    private function visitCombination(array $combination): ?array
    {
        $alreadyCheckedBookings = [];

        foreach ($combination as $bookingRequest) {
            foreach ($alreadyCheckedBookings as $alreadyCheckedBookingRequest) {
                if ($bookingRequest->dateRange->overlaps($alreadyCheckedBookingRequest->dateRange)) {
                    return null;
                }
            }

            $alreadyCheckedBookings[] = $bookingRequest;
        }

        return $combination;
    }
}
