<?php

namespace App\Enums;

enum GrantType
{
    case authorization;
    case refresh_token;
}
