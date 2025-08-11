<?php

declare(strict_types=1);

namespace App\Application\Command;

/**
 * @codeCoverageIgnore
 */
final readonly class StatsCommand
{
    /**
     * @param list<array{
     *     request_id: string,
     *     check_in: string,
     *     nights: int,
     *     selling_rate: int,
     *     margin: int
     * }> $data
     */
    public function __construct(public array $data) {
    }
}
