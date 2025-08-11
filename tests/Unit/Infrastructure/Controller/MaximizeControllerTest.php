<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Controller;

use App\Application\Service\MaximizeService;
use App\Domain\Service\BookingsCombinator;
use App\Domain\Service\ProfitCalculator;
use App\Infrastructure\Controller\MaximizeController;
use App\Infrastructure\Controller\Model\BookingRequestDto;
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
        $bookingRequestList = [
            new BookingRequestDto(
                'bookata_XY123',
                '2020-01-01',
                5,
                200,
                20
            ),
            new BookingRequestDto(
                'kayete_PP234',
                '2020-01-04',
                4,
                156,
                5
            ),
            new BookingRequestDto(
                'atropote_AA930',
                '2020-01-04',
                4,
                150,
                6
            ),
            new BookingRequestDto(
                'acme_AAAAA',
                '2020-01-10',
                4,
                160,
                30
        )];
        $expectedResponse = json_encode([
            'request_ids' => [
                'bookata_XY123',
                'acme_AAAAA',
            ],
            'total_profit' => 88,
            'avg_night' => 10,
            'min_night' => 8,
            'max_night' => 12,
        ], JSON_THROW_ON_ERROR);

        $response = $controller->__invoke($bookingRequestList);

        self::assertSame($expectedResponse, $response->getContent());
    }

}
