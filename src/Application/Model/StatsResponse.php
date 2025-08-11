<?php

declare(strict_types=1);

namespace App\Application\Model;

final readonly class StatsResponse
{
    public function __construct(public float $average, public float $minimum, public float $maximum)
    {
    }

}
