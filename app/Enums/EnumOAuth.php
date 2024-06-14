<?php

namespace App\Enums;

enum EnumOAuth
{
    case Google;
    public function toString()
    {
        return match ($this) {
            EnumOAuth::Google => "google"
        };
    }
}
