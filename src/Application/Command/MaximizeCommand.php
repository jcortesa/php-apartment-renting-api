<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Infrastructure\Controller\Model\BookingRequestDto;

/**
 * @codeCoverageIgnore
 */
final readonly class MaximizeCommand
{
    /**
     * @param list<BookingRequestDto> $bookings
     */
    public function __construct(public array $bookings) {
    }
}
