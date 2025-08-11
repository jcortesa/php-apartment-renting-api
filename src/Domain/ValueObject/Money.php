<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

/**
 * @codeCoverageIgnore
 */
final readonly class Money
{
    public function __construct(public float $amount)
    {
    }
}
