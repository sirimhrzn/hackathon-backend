<?php

namespace App\Http\Services\ServiceInterface;

use App\Enums\GrantType;
use League\OAuth2\Client\Token\AccessToken;

interface OAuthServiceInterface
{
    public function get_provider();
    public function get_authorization_url(): string;
    public function get_access_token($code, GrantType $type): AccessToken;
    public function get_user_detail(AccessToken $token): array;
}
