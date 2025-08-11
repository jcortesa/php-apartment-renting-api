<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\BookingRequest;

final readonly class BookingsCombinator
{
    /**
     * @param list<BookingRequest> $bookingRequestList
     *
     * @return list<list<BookingRequest>>
     */
    public function getValidCombinations(array $bookingRequestList): array
    {
        return array_filter(
            $this->generateCombinations($bookingRequestList),
            fn (array $combination): ?array => $this->visitCombination($combination)
        );
    }

    /**
     * @param list<BookingRequest> $combination
     *
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

    /**
     * @param list<BookingRequest> $array
     *
     * @return list<list<BookingRequest>>
     */
    private function generateCombinations(array $array): array
    {
        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                $results[] = array_merge([$element], $combination);
            }
        }

        return $results;
    }
}
