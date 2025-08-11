<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\Routing\Attribute\Route;

final readonly class MaximizeController
{
    #[Route('/maximize', methods: ['POST'])]
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}
