<?php

declare(strict_types=1);

namespace App\Application\Model;

final readonly class MaximizeResponse
{
    public function __construct(
        public array $requestIds,
        public float $totalProfit,
        public float $average,
        public float $minimum,
        public float $maximum
    ) {
    }
}
