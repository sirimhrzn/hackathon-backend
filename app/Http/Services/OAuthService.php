<?php

namespace App\Http\Services;

use App\Enums\EnumOAuth;
use App\Enums\ExceptionEnum;
use App\Http\Services\ServiceInterface\OAuthServiceInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google;

class OAuthService
{
    public OAuthServiceInterface $provider;

    public function set_provider(EnumOAuth $oauth_provider)
    {
        $provider = match ($oauth_provider) {
            EnumOAuth::Google => new OAuthGoogle(),
            default => throw ExceptionEnum::Unimplemented
        };
        $this->provider = $provider;
        return $this;
    }
}
