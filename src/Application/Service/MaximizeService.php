<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\MaximizeCommand;
use App\Application\Query\MaximizeQuery;
use App\Domain\Entity\BookingRequest;
use App\Domain\Service\BookingsCombinator;
use App\Domain\Service\ProfitCalculator;
use App\Domain\ValueObject\DateRange;
use App\Domain\ValueObject\Money;

final readonly class MaximizeService
{
    public function __construct(
        private ProfitCalculator $profitCalculator,
        private BookingsCombinator $bookingsCombinator
    )
    {
    }

    public function run(MaximizeCommand $maximizeCommand): MaximizeQuery
    {
        /** @var list<BookingRequest> $bookingRequestList */
        $bookingRequestList = array_map(static fn($data) => new BookingRequest(
            $data['request_id'],
            new DateRange($data['check_in'], $data['nights']),
            new Money($data['selling_rate']),
            $data['margin']
        ), $maximizeCommand->data);

        $validCombinations = $this->bookingsCombinator->getValidCombinations($bookingRequestList);
        $maxProfitCombination = $this->getMaxProfitCombination($validCombinations);

        return $this->createMaximizeQuery($maxProfitCombination);
    }

    private function getMaxProfitCombination(array $validCombinations): array
    {
        $totalProfitPerCombinationList = array_map(
            fn (array $combination) => array_sum(array_map(
                fn(BookingRequest $bookingRequest) => $this->profitCalculator->calculateTotalProfit($combination),
                $combination
            )),
            $validCombinations
        );

        $maxProfitKey = array_keys($totalProfitPerCombinationList, max($totalProfitPerCombinationList))[0];

        return $validCombinations[$maxProfitKey] ?? [];
    }

    private function createMaximizeQuery(array $maxProfitCombination): MaximizeQuery
    {
        $requestIds = array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->requestId,
            $maxProfitCombination
        );

        $totalProfit = array_sum(array_map(
            static fn(BookingRequest $bookingRequest) => $bookingRequest->calculateProfit()->amount,
            $maxProfitCombination
        ));

        [$average, $minimum, $maximum] = $this->profitCalculator->calculateProfitMetrics($maxProfitCombination);

        return new MaximizeQuery($requestIds, $totalProfit, $average, $minimum, $maximum);
    }
}
