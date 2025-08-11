<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\Command\StatsCommand;
use App\Application\Query\StatsQuery;
use App\Application\Service\StatsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatsService::class)]
final class StatsServiceTest extends TestCase
{
    public function testCalculateAverage(): void
    {
        $statsService = new StatsService();
        $statsCommand = new StatsCommand([[
            'request_id' => 'bookata_XY123',
            'check_in' => '2020-01-01',
            'nights' => 5,
            'selling_rate' => 200,
            'margin' => 20
        ]]);
        $expectedResult = new StatsQuery(8, 8,8);

        $statsQuery = $statsService->run($statsCommand);

        self::assertEqualsCanonicalizing($expectedResult, $statsQuery);
    }
}
