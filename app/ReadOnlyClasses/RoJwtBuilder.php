<?php

namespace App\ReadOnlyClasses;

final readonly class RoJwtBuilder
{
    public function __construct(
        public array $claims,
        public int $expiration_time = 84600
    ) {
    }
}
