<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Enums\EnumOAuth;
use Illuminate\Support\Facades\DB;
use App\Enums\ExceptionEnum;
use App\Exceptions\CustomException;
use App\ReadOnlyClasses\ROResponse;
use App\Helpers\ResponseBuilder;
use App\Http\Services\OAuthService;
use App\Models\OAuthToken;
use App\Models\Stores;
use App\Models\User;
use App\Models\Vendors;
use App\Models\VendorUsers;
use App\ReadOnlyClasses\RoJwtBuilder;
use App\Http\Services\JwtService;
use App\Enums\GrantType;

class AuthenticationController extends Controller
{
    public function vendor_sign_up(AuthenticationRequest $request)
    {
        $data = (object)$request->validated();
        DB::beginTransaction();
    }
    public function vendor_login(AuthenticationRequest $request)
    {
        $data = (object)$request->validated();
        $grant_type = $data->grant_type;
        $response = match ($grant_type) {
            'password' => $this->handle_password_login($data),
            default => throw ExceptionEnum::Unimplemented
        };
        return response()
            ->json($response->message, $response->status_code);
    }
    private function handle_password_login(object $data): ROResponse
    {
        $response = [];
        $user = $this->user->exists('email', $data->email);
        if (!$user) {
            throw ExceptionEnum::InvalidUser;
        }
        // verify user hash
        $hash_check  = "";
        if(!$hash_check) {
            throw ExceptionEnum::InvalidCredentials;
        }
        $vendor = $user->vendor->toArray();
        if(empty($vendor)) {
            throw ExceptionEnum::ResourceDoesNotExist;
        }
        // get user role scope
        $jwt = new JwtService();
        $jwt_payload = new RoJwtBuilder([
            'vendor_id' => $vendor->id,
            'scope'     => 'default',
        ]);
        $token = $jwt->generateJWT($jwt_payload);
        $response['access_token'] = $token->toString();
        return ResponseBuilder::buildResponse($response, 200, 'Logged in successfully');
    }

    public function callbackHandler(AuthenticationRequest $request)
    {
        $provider = $request->route('provider');
        $response = match ($provider) {
            'google' => $this->oauth_handler($request, EnumOAuth::Google),
            default => throw ExceptionEnum::Unimplemented
        };
        return response()->json($response->message, $response->status_code);
    }

    private function oauth_handler(AuthenticationRequest $request, EnumOAuth $oauth_provider): ROResponse
    {
        $code = $request->code;
        $oauth_service = (new OAuthService())->set_provider($oauth_provider);
        $token = $oauth_service->provider->get_access_token($code, GrantType::authorization);
        $user_detail = $oauth_service->provider->get_user_detail($token);

        $user = User::where('email', $user_detail['email'])->first();

        $access_token = $token->getToken();
        $refresh_token = $token->getRefreshToken();
        $expiry_time = $token->getExpires();

        $data = [
            'access_token'  => $access_token,
            'refresh_token' => $refresh_token,
            'provider'      => $oauth_provider->toString(),
            'expiry_time'   => $expiry_time
        ];
        if($user != null) {
            OAuthToken::where('user_id', $user->id)->update($data);
        }

        if($user == null) {
            DB::beginTransaction();

            $user = [
                'name'           => $request->input('name') ?? "test_name",
                'number'         => $request->input('number') ?? "test_number",
                'verified_email' => $user_detail['email_verified'] ? 'y' : 'n',
                'email'          => $user_detail['email'],
                'password'       => null,
                'providers'      => $oauth_provider->toString()
            ];

            $store_name = $request->input('store_name') ?? "test_store_name";
            $user_id = User::create($user)->id;
            $vendor_id = Vendors::create(['name' => $store_name])->id;
            VendorUsers::create(['user_id' => $user_id,'vendor_id' => $vendor_id ]);

            // create super admin role for the owner
            Stores::create(['name' => $store_name,'vendor_id' => $vendor_id]);
            $data['user_id'] = $user_id;
            OAuthToken::create($data);
            DB::commit();
        }
        $token_data = [
            'user_id' => is_null($user) ? $data['user_id'] : $user->id,
        ];
        $jwt_builder = new RoJwtBuilder([
            'provider'            => $oauth_provider->toString(),
            'user_id'             => $token_data['user_id']
        ], $data['expiry_time']);
        $jwt = (new JwtService())->generateJWT($jwt_builder);
        return ResponseBuilder::buildResponse([
            'access_token'  => $jwt->toString(),
            'refresh_token' => $data['refresh_token'],
        ], 200, 'Successful token retrieval');
    }
    public function getAuthorizationURL(AuthenticationRequest $request)
    {
        $provider = $request->route('provider');
        $oauth_service = new OAuthService();
        $new_vendor_details = $request->all();
        $oauth_service = match ($provider) {
            'google' => $oauth_service->set_provider(EnumOAuth::Google),
            default => throw new CustomException(ExceptionEnum::Unimplemented)
        };
        $auth_url = $oauth_service->provider->get_authorization_url();
        $response = ResponseBuilder::buildResponse([
            'authentication_url' => $auth_url
        ], 200, 'Url generated successfully');
        return response()->json($response->message, $response->status_code);
    }
    public function refreshToken(AuthenticationRequest $request)
    {
        $data = (object)$request->validated();
        $oauth_service = new OAuthService();
        $refresh_token = $data->refresh_token;
        $oauth_token = OAuthToken::where('refresh_token', $refresh_token)->where('expiry_time', '>', time())
                                                                        ->first();
        if($oauth_token == null) {
            return response()->json([
                'message' => 'Invalid refresh token'
            ], 500);
        }
        $oauth_provider = match ($oauth_token->provider) {
            'google' => EnumOAuth::Google,
            default =>  throw new CustomException(ExceptionEnum::Unimplemented)

        };
        $data = [
            'user_id' => $oauth_token->user_id
        ];
        $response = $this->getJWT(GrantType::refresh_token, $oauth_provider, $data, $oauth_token->refresh_token);
        return response()->json($response->message, $response->status_code);
    }
    private function getJWT(GrantType $grant_type, EnumOAuth $oauth_provider, array $data, string $code): ROResponse
    {
        $oauth_service = new OAuthService();
        $oauth_service = match ($oauth_provider) {
            EnumOAuth::Google => $oauth_service->set_provider($oauth_provider),
            default => throw new CustomException(ExceptionEnum::Unimplemented)
        };
        $token = $oauth_service->provider->get_access_token($code, $grant_type);
        $token_data = [
            'access_token'  => $token->getToken(),
            'expiry_time'   => $token->getExpires()
        ];
        OAuthToken::where('user_id', $data['user_id'])->where('revoked', 'n')
                                                     ->update($token_data);

        $jwt_builder = new RoJwtBuilder([
            'provider'            => $oauth_provider->toString(),
            'user_id'             => $data['user_id']
        ], $token_data['expiry_time']);
        $jwt = (new JwtService())->generateJWT($jwt_builder);
        return ResponseBuilder::buildResponse([
            'access_token'  => $jwt,
            // dont return refresh token
            // 'refresh_token' => $token_data['refresh_token'],
        ], 200, 'Successful token retrieval');
    }
    private function sign_up(AuthenticationRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
            $user = [
                'name'           => $data['name'],
                'number'         => $request->input('number') ?? "test_number",
                'verified_email' => 'y',
                'email'          => $data['email'],
                'password'       => $data['password'],
                'providers'      => null
            ];

            $store_name = $data['store_name'];
            $user_id = User::create($user)->id;
            $vendor_id = Vendors::create(['name' => $store_name])->id;
            VendorUsers::create(['user_id' => $user_id,'vendor_id' => $vendor_id ]);
            // create super admin role for the owner
            Stores::create(['name' => $store_name,'vendor_id' => $vendor_id]);
            $data['user_id'] = $user_id;
            OAuthToken::create($data);
            DB::commit();

    }
}
