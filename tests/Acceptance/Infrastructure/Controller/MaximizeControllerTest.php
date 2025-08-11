<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Infrastructure\Controller;

use App\Infrastructure\Controller\MaximizeController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(MaximizeController::class)]
final class MaximizeControllerTest extends WebTestCase
{
    public function testMaximizeEndpoint(): void
    {
        $client = static::createClient();
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
        $expectedResult = json_encode([
            'request_ids' => [
                'bookata_XY123',
                'acme_AAAAA',
            ],
            'total_profit' => 88,
            'avg_night' => 10,
            'min_night' => 8,
            'max_night' => 12,
        ], JSON_THROW_ON_ERROR);

        $client->request('POST', '/maximize', server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: $content);

        self::assertSame($expectedResult, $client->getResponse()->getContent());
    }
}
