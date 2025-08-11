<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Model\MaximizeRequest;
use App\Application\Model\MaximizeResponse;
use App\Domain\Entity\BookingRequest;

final readonly class MaximizeService
{
    public function run(MaximizeRequest $maximizeRequest): MaximizeResponse
    {
        /** @var list<BookingRequest> $bookingRequestList */
        $bookingRequestList = array_map(static fn($data) => new BookingRequest(
            $data['request_id'],
            $data['check_in'],
            $data['nights'],
            $data['selling_rate'],
            $data['margin']
        ), $maximizeRequest->data);

        $combinations = self::getArrayCombinations($bookingRequestList);
        $validCombinations = self::getValidCombinations($combinations);

        $maxProfitCombination = self::getMaxProfitCombination($validCombinations);

        return self::createMaximizeResponse($maxProfitCombination);
    }

    private static function calculateProfitPerNight(int $sellingRate, int $margin, int $nights): float
    {
        return ($sellingRate * ($margin / 100)) / $nights;
    }

    private static function calculateTotalProfit(array $bookingRequestList): float
    {
        return array_reduce($bookingRequestList, static function (float $carry, BookingRequest $bookingRequest) {
            return $carry + $bookingRequest->sellingRate * $bookingRequest->margin;
        }, 0.0);
    }

    /**
     * @param list<BookingRequest> $bookingRequestList
     *
     * @return list<float>
     */
    private static function getMaximizeData(array $bookingRequestList): array
    {
        $profitsPerNightList = array_map(
            static fn(BookingRequest $bookingRequest) => self::calculateProfitPerNight($bookingRequest->sellingRate, $bookingRequest->margin, $bookingRequest->nights),
            $bookingRequestList
        );

        return [
            round(array_sum($profitsPerNightList) / count($profitsPerNightList), 2),
            min($profitsPerNightList),
            max($profitsPerNightList),
        ];
    }

    private static function getArrayCombinations($array): array
    {
        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                $results[] = array_merge([$element], $combination);
            }
        }

        return $results;
    }

    private static function getValidCombinations(array $combinations): array
    {
        $validCombinations = [];

        foreach ($combinations as $combination) {
            $isValid = true;
            $combinationCheckDataList = [];

            foreach ($combination as $bookingRequest) {
                $currentCheckIn = \DateTimeImmutable::createFromFormat('Y-m-d', $bookingRequest->checkIn);
                $currentCheckOut = $currentCheckIn->add(new \DateInterval('P'.$bookingRequest->nights.'D'));

                $isOverlapping = false;

                foreach ($combinationCheckDataList as $checkData) {
                    $checkInDate = $checkData['check_in_date'];
                    $checkOutDate = $checkData['check_out_date'];

                    if ($currentCheckIn < $checkOutDate && $currentCheckOut > $checkInDate) {
                        $isOverlapping = true;
                        break;
                    }
                }

                if ($isOverlapping) {
                    $isValid = false;
                    break;
                }

                $combinationCheckDataList[] = [
                    'check_in_date' => $currentCheckIn,
                    'check_out_date' => $currentCheckOut,
                ];
            }

            if ($isValid) {
                $validCombinations[] = $combination;
            }
        }

        return $validCombinations;
    }

    private static function getMaxProfitCombination(array $validCombinations): array
    {
        $profitPerCombinationList = array_map(
            static fn(array $combination) => array_sum(array_map(
                static fn(BookingRequest $bookingRequest) => self::calculateTotalProfit($combination),
                $combination
            )),
            $validCombinations
        );

        $maxProfitKey = array_keys($profitPerCombinationList, max($profitPerCombinationList))[0];

        return $validCombinations[$maxProfitKey] ?? [];
    }

    private static function createMaximizeResponse(array $maxProfitCombination): MaximizeResponse
    {
        $requestIds = array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->requestId,
            $maxProfitCombination
        );

        $totalProfit = array_sum(array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->sellingRate * ($bookingRequest->margin / 100),
            $maxProfitCombination
        ));

        [$average, $minimum, $maximum] = self::getMaximizeData($maxProfitCombination);

        return new MaximizeResponse($requestIds, $totalProfit, $average, $minimum, $maximum);
    }
}
