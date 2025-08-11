<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\MaximizeCommand;
use App\Application\Query\MaximizeQuery;
use App\Domain\Entity\BookingRequest;
use App\Domain\Service\ProfitCalculator;

final readonly class MaximizeService
{
    public function __construct(private ProfitCalculator $profitCalculator)
    {
    }

    public function run(MaximizeCommand $maximizeRequest): MaximizeQuery
    {
        /** @var list<BookingRequest> $bookingRequestList */
        $bookingRequestList = array_map(static fn($data) => new BookingRequest(
            $data['request_id'],
            $data['check_in'],
            $data['nights'],
            $data['selling_rate'],
            $data['margin']
        ), $maximizeRequest->data);

        $validCombinations = $this->getValidCombinations($bookingRequestList);
        $maxProfitCombination = $this->getMaxProfitCombination($validCombinations);

        return $this->createMaximizeQuery($maxProfitCombination);
    }

    /**
     * @param list<BookingRequest> $bookingRequestList
     *
     * @return list<list<BookingRequest>>
     */
    private function getValidCombinations(array $bookingRequestList): array
    {
        $combinations = $this->getArrayCombinations($bookingRequestList);

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

    private function getArrayCombinations($array): array
    {
        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                $results[] = array_merge([$element], $combination);
            }
        }

        return $results;
    }

    private function getMaxProfitCombination(array $validCombinations): array
    {
        $profitPerCombinationList = array_map(
            fn(array $combination) => array_sum(array_map(
                fn(BookingRequest $bookingRequest) => $this->profitCalculator->calculateTotalProfit($combination),
                $combination
            )),
            $validCombinations
        );

        $maxProfitKey = array_keys($profitPerCombinationList, max($profitPerCombinationList))[0];

        return $validCombinations[$maxProfitKey] ?? [];
    }

    private function createMaximizeQuery(array $maxProfitCombination): MaximizeQuery
    {
        $requestIds = array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->requestId,
            $maxProfitCombination
        );

        $totalProfit = array_sum(array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->sellingRate * ($bookingRequest->margin / 100),
            $maxProfitCombination
        ));

        [$average, $minimum, $maximum] = $this->profitCalculator->calculateProfitMetrics($maxProfitCombination);

        return new MaximizeQuery($requestIds, $totalProfit, $average, $minimum, $maximum);
    }
}
