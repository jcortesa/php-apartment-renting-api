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
        $combinationCheckData = [];

        foreach ($combination as $bookingRequest) {
            $currentCheckIn = \DateTimeImmutable::createFromFormat('Y-m-d', $bookingRequest->checkIn);
            $currentCheckOut = $currentCheckIn->add(new \DateInterval('P' . $bookingRequest->nights . 'D'));

            foreach ($combinationCheckData as ['check_in_date' => $checkIn, 'check_out_date' => $checkOut]) {
                if ($currentCheckIn < $checkOut && $currentCheckOut > $checkIn) {
                    return null;
                }
            }

            $combinationCheckData[] = [
                'check_in_date' => $currentCheckIn,
                'check_out_date' => $currentCheckOut,
            ];
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
