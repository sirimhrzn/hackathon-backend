<?php

namespace App\ReadOnlyClasses;

readonly class ROException
{
    public function __construct(
        public string $message,
        public int $status_code
    ) {
    }
}
