<?php

declare(strict_types=1);

namespace App\Application\Model;

final readonly class StatsRequest
{
    public function __construct(public array $data) {
    }
}
