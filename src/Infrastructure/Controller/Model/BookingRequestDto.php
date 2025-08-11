<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class BookingRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[SerializedName('request_id')]
        public string $requestId,

        #[Assert\NotBlank]
        #[Assert\Date]
        #[SerializedName('check_in')]
        public string $checkIn,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $nights,

        #[Assert\NotBlank]
        #[Assert\Positive]
        #[SerializedName('selling_rate')]
        public float $sellingRate,

        #[Assert\NotBlank]
        #[Assert\Range(min: 0, max: 100)]
        public float $margin
    )
    {}
}
