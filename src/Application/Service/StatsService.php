<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Model\StatsRequest;
use App\Application\Model\StatsResponse;

final class StatsService
{
    public function run(StatsRequest $statsRequest): StatsResponse
    {
        // @TODO implement the logic to retrieve statistics based on the request.
        return new StatsResponse();
    }
}
