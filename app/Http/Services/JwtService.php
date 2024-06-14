<?php

namespace App\Http\Services;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use App\Enums\ExceptionEnum;
use App\ReadOnlyClasses\RoJwtBuilder;
use Lcobucci\JWT\UnencryptedToken;
use Throwable;

class JwtService
{
    public function generateJWT(RoJwtBuilder $jwt_options): UnencryptedToken
    {
        $configuration = $this->getJWTConfiguration();
        $token_builder = $configuration->builder();
        $now   = new DateTimeImmutable();
        $date = date('Y-m-d H:i:s', $jwt_options->expiration_time);
        $token = $token_builder->issuedAt($now);
        foreach($jwt_options->claims as $key => $value) {
            $token = $token->withClaim($key, $value);
        }
        $token->expiresAt($now->createFromFormat('Y-m-d H:i:s', $date));
        $token = $token->getToken($configuration->signer(), $configuration->signingKey());
        return $token;
    }

    public function validateJWT(string $token)
    {
        try {
            $configuration = $this->getJWTConfiguration();
            $token = $configuration->parser()->parse($token);
            $validator = $configuration->validator();
            $validation = $validator->validate($token, new SignedWith($configuration->signer(), $configuration->signingKey()));
            return $validation;
        } catch (Throwable $exception) {
            // log exception
            throw ExceptionEnum::InvalidToken;
        }
    }

    private function getJWTConfiguration(): Configuration
    {
        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText(public_path('out')),
            InMemory::plainText(public_path('out.pub')),
        );
        return $configuration;
    }
}
