<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\DateRange;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateRange::class)]
final class DateRangeTest extends TestCase
{
    public function testOverlaps(): void
    {
        $dateRange1 = new DateRange('2023-10-01', 5);
        $dateRange2 = new DateRange('2023-10-03', 4);
        $dateRange3 = new DateRange('2023-10-07', 2);

        $this->assertTrue($dateRange1->overlaps($dateRange2));
        $this->assertFalse($dateRange1->overlaps($dateRange3));
        $this->assertTrue($dateRange2->overlaps($dateRange1));
        $this->assertFalse($dateRange2->overlaps($dateRange3));
    }

}
