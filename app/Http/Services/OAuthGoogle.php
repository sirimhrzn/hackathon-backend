<?php

namespace App\Http\Services;

use App\Enums\EnumOAuth;
use App\Http\Services\ServiceInterface\OAuthServiceInterface;
use App\Enums\GrantType;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;

class OAuthGoogle implements OAuthServiceInterface
{
    public AbstractProvider $provider;

    public function __construct()
    {
        $this->set_provider();
    }

    public function set_provider()
    {
        $provider = new Google(config('oauth2.google'));
        $this->provider = $provider;
    }
    public function get_authorization_url(): string
    {
        $auth_url = $this->provider->getAuthorizationUrl([
            'prompt'      => 'consent',
            'access_type' => 'offline'
        ]);
        return $auth_url;
    }
    public function get_access_token($code, GrantType $type): AccessToken
    {
        $data = [];
        match ($type) {
            GrantType::authorization => $data = [
                'type' => 'authorization_code',
                'code' => [
                    'code' => $code
                ]
            ],
            GrantType::refresh_token => $data = [
                'type' => new RefreshToken(),
                'code' => [
                    'refresh_token' => $code
                ]
            ]
        };
        $access_token = $this->provider->getAccessToken($data['type'], $data['code']);
        return $access_token;
    }
    public function get_provider()
    {
    }
    public function get_user_detail($token): array
    {
        $user_detail = $this->provider->getResourceOwner($token)->toArray();
        return $user_detail;
    }

}
