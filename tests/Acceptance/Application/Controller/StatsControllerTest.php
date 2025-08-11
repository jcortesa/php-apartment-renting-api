<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Application\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class StatsControllerTest extends WebTestCase
{
    #[DataProvider('provideStatsEndpointCases')]
    public function testStatsEndpoint(string $content, string $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/stats', content: $content);

        self::assertSame($expectedResult, $client->getResponse()->getContent());
    }

    /**
     * @return list<list<string>>
     */
    public static function provideStatsEndpointCases(): array
    {
        return [
            [
                json_encode([
                    [
                        'request_id' => 'bookata_XY123',
                        'check_in' => '2020-01-01',
                        'nights' => 5,
                        'selling_rate' => 200,
                        'margin' => 20,
                    ],
                    [
                        'request_id' => 'kayete_PP234',
                        'check_in' => '2020-01-04',
                        'nights' => 4,
                        'selling_rate' => 156,
                        'margin' => 22,
                    ],
                ], JSON_THROW_ON_ERROR),
                json_encode([
                    'avg_night' => 8.29,
                    'min_night' => 8,
                    'max_night' => 8.58,
                ], JSON_THROW_ON_ERROR),
            ],
            [
                json_encode([
                    [
                        'request_id' => 'bookata_XY123',
                        'check_in' => '2020-01-01',
                        'nights' => 1,
                        'selling_rate' => 50,
                        'margin' => 20,
                    ],
                    [
                        'request_id' => 'kayete_PP234',
                        'check_in' => '2020-01-04',
                        'nights' => 1,
                        'selling_rate' => 55,
                        'margin' => 22,
                    ],
                    [
                        'request_id' => 'trivoltio_ZX69',
                        'check_in' => '2020-01-07',
                        'nights' => 1,
                        'selling_rate' => 49,
                        'margin' => 21,
                    ],
                ], JSON_THROW_ON_ERROR),
                json_encode([
                    'avg_night' => 10.80,
                    'min_night' => 10,
                    'max_night' => 12.1,
                ], JSON_THROW_ON_ERROR),
            ],
        ];
    }
}
