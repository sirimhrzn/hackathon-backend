<?php

namespace App\ReadOnlyClasses;

readonly class ROResponse
{
    public function __construct(
        public array $message,
        public int $status_code,
    ) {
    }
}
