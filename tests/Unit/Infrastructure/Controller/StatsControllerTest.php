<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Controller;

use App\Application\Service\StatsService;
use App\Infrastructure\Controller\StatsController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(StatsController::class)]
final class StatsControllerTest extends TestCase
{
    public function testWhenInvokeThenReturnProperResponse(): void
    {
        $request = new Request(content: json_encode([
            [
                'request_id' => 'req-1',
                'check_in' => '2023-10-01',
                'nights' => 5,
                'selling_rate' => 1000,
                'margin' => 20,
            ],
            [
                'request_id' => 'req-2',
                'check_in' => '2023-10-05',
                'nights' => 3,
                'selling_rate' => 1500,
                'margin' => 15,
            ],
            [
                'request_id' => 'req-3',
                'check_in' => '2023-10-10',
                'nights' => 2,
                'selling_rate' => 800,
                'margin' => 10,
            ],
        ], JSON_THROW_ON_ERROR));
        $statsService = new StatsService();
        $controller = new StatsController($statsService);
        $expectedResponse = json_encode([
            'avg_night' => 51.67,
            'min_night' => 40,
            'max_night' => 75
        ], JSON_THROW_ON_ERROR);

        $response = $controller->__invoke($request);

        self::assertSame($expectedResponse, $response->getContent());
    }
}
