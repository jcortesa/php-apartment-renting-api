<?php

declare(strict_types=1);

namespace App\Application\Query;

/**
 * @codeCoverageIgnore
 */
final readonly class MaximizeQuery
{
    /**
     * @param list<string> $requestIds
     */
    public function __construct(
        public array $requestIds,
        public float $totalProfit,
        public float $average,
        public float $minimum,
        public float $maximum
    ) {
    }
}
