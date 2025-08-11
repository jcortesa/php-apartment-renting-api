<?php

declare(strict_types=1);

namespace App\Application\Query;

final readonly class StatsQuery
{
    public function __construct(public float $average, public float $minimum, public float $maximum)
    {
    }
}
