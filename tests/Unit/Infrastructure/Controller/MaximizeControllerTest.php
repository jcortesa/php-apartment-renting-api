<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Controller;

use App\Application\Service\MaximizeService;
use App\Domain\Service\BookingsCombinator;
use App\Domain\Service\ProfitCalculator;
use App\Infrastructure\Controller\MaximizeController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(MaximizeController::class)]
final class MaximizeControllerTest extends TestCase
{
    public function testWhenInvokeThenReturnProperResponse(): void
    {
        $profitCalculator = new ProfitCalculator();
        $bookingsCombinator = new BookingsCombinator();
        $maximizeService = new MaximizeService($profitCalculator, $bookingsCombinator);
        $controller = new MaximizeController($maximizeService);
        $content = json_encode([
            [
                'request_id' => 'bookata_XY123',
                'check_in' => '2020-01-01',
                'nights' => 5,
                'selling_rate' => 200,
                'margin' => 20
            ],
            [
                'request_id' => 'kayete_PP234',
                'check_in' => '2020-01-04',
                'nights' => 4,
                'selling_rate' => 156,
                'margin' => 5
            ],
            [
                'request_id' => 'atropote_AA930',
                'check_in' => '2020-01-04',
                'nights' => 4,
                'selling_rate' => 150,
                'margin' => 6
            ],
            [
                'request_id' => 'acme_AAAAA',
                'check_in' => '2020-01-10',
                'nights' => 4,
                'selling_rate' => 160,
                'margin' => 30
            ]
        ], JSON_THROW_ON_ERROR);
        $expectedResponse = json_encode([
            'request_ids' => [
                'acme_AAAAA',
                'bookata_XY123',
            ],
            'total_profit' => 88,
            'avg_night' => 10,
            'min_night' => 8,
            'max_night' => 12,
        ], JSON_THROW_ON_ERROR);
        $request = new Request(content: $content);

        $response = $controller->__invoke($request);

        self::assertSame($expectedResponse, $response->getContent());
    }

}
